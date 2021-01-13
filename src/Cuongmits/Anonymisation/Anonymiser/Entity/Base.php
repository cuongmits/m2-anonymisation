<?php
namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Exception\AnonymisationException;
use Zend_Db_Expr;
use Exception;

abstract class Base implements BaseInterface
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var AdapterInterface */
    protected $connection;

    public function __construct(ResourceConnection $resourceConnection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Build expressions used as parts of a SQL SET clause
     *
     * @param array $anonymiseColumns New values of columns, keyed by column name
     *
     * @return array|Zend_Db_Expr[]
     */
    public function buildBindExpressions(array $anonymiseColumns): array
    {
        $parts = [];

        foreach ($anonymiseColumns as $column => $value) {
            if ($column === $this->getMarkup()) {
                $parts[$column] = new Zend_Db_Expr($this->connection->quoteInto('?', $value));
            } else {
                $parts[$column] = new Zend_Db_Expr($this->connection->quoteInto(
                    sprintf('IF(%1$s IS NULL, NULL, ?)', $this->connection->quoteIdentifier($column)),
                    $value
                ));
            }
        }

        if ($this->updatedDateExists()) {
            $parts['updated_at'] = new Zend_Db_Expr($this->connection->quoteIdentifier('updated_at'));
        }

        return $parts;
    }

    /**
     * @param int $id
     *
     * @return int
     *
     * @throws AnonymisationException
     */
    public function process(int $id): int
    {
        try {
            $rowCount = $this->connection->update(
                $this->getTableName(),
                $this->buildBindExpressions($this->getAnonymisationColumns()),
                [sprintf('%s = ?', $this->getCondition()) => $id]
            );
            $this->logger->info(
                sprintf('%s with %s=%s has been anonymised.', $this->getEntityName(), $this->getCondition(), $id),
                ['count' => $rowCount]
            );
        } catch (Exception $e) {
            $this->logger->error(
                sprintf('Sub-anonymisation for %s %s failed.', $this->getEntityName(), $id),
                ['exception' => $e]
            );

            throw new AnonymisationException(__('%1 %2 anonymisation failed.', $this->getEntityName(), $id));
        }

        return $rowCount;
    }

    public function getMarkup(): string
    {
        return '';
    }

    abstract public function getTableName(): string;
    abstract public function getEntityName(): string;
    abstract public function getCondition(): string;
    abstract public function updatedDateExists(): bool;
    abstract public function getAnonymisationColumns(): array;
}
