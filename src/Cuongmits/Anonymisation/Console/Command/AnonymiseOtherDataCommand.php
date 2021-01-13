<?php

namespace Cuongmits\Anonymisation\Console\Command;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cuongmits\Anonymisation\Anonymiser\OtherAnonymiser;
use Cuongmits\Anonymisation\Provider\ConfigProvider;

class AnonymiseOtherDataCommand extends Command
{
    /** @var State */
    private $appState;

    /** @var OtherAnonymiser */
    private $otherAnonymiser;

    /** @var ConfigProvider */
    private $configProvider;

    public function __construct(
        State $appState,
        OtherAnonymiser $otherAnonymiser,
        ConfigProvider $configProvider
    ) {
        parent::__construct();

        $this->appState = $appState;
        $this->otherAnonymiser = $otherAnonymiser;
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('toom:anonymise-other-data')
            ->setDescription(
                'Anonymise general guest customer personal data that has a special retention time defined'
            )
            ->setHelp(
                'Replaces "other" personal data related to guest customers with
                anonymized data, after maximum retention time has been reached.
                "Other data" has a retention time different from the general case, and includes abandoned carts
                and cancelled orders.'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     *
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode(Area::AREA_GLOBAL);
        } catch (Exception $e) {
            $output->writeln('<info>Area Code is already set.</info>');
        }

        if (!$this->configProvider->isAnonymisationEnabled()) {
            $output->writeln('<info>Anonymisation feature is disabled at the moment.</info>');

            return 0;
        }

        $output->writeln('<info>Starting anonymisation process...</info>');

        $counter = $this->otherAnonymiser->run();

        $quoteCounter = $counter['quote'];
        $orderCounter = $counter['order'];

        $output->writeln("<info>$quoteCounter quote(s) and $orderCounter order(s) have been anonymised.</info>");
        $output->writeln('<info>Done.</info>');

        return 0;
    }
}
