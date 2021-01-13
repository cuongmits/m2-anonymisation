<?php

namespace Cuongmits\Anonymisation\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cuongmits\Anonymisation\Anonymiser\GeneralAnonymiser;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use Exception;

class AnonymiseGeneralDataCommand extends Command
{
    /** @var State */
    private $appState;

    /** @var ConfigProvider */
    private $configProvider;

    /** @var GeneralAnonymiser */
    private $generalAnonymiser;

    public function __construct(
        State $appState,
        GeneralAnonymiser $generalAnonymiser,
        ConfigProvider $configProvider
    ) {
        parent::__construct();

        $this->appState = $appState;
        $this->generalAnonymiser = $generalAnonymiser;
        $this->configProvider = $configProvider;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('toom:anonymise-general-data')
            ->setDescription(
                'Anonymise general guest customer personal data that does not have a special retention time'
            )
            ->setHelp(
                'Replaces general personal data related to guest customers with
                anonymized data, after maximum retention time has been reached.
                "General data" means all data that has no special retention time.'
            );
    }

    /**
     * @param InputInterface $input
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

        $counter = $this->generalAnonymiser->run();

        $quoteCounter = $counter['quote'];
        $orderCounter = $counter['order'];

        $output->writeln("<info>$quoteCounter quote(s) and $orderCounter order(s) have been anonymised.</info>");
        $output->writeln('<info>Done.</info>');

        return 0;
    }
}
