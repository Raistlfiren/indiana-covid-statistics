<?php


namespace App\Command;


use App\Service\DataPuller;
use App\Service\INDataHubPuller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PullDataCommand extends Command
{
    protected static $defaultName = 'app:pull-data';

    /**
     * @var DataPuller
     */
    private $dataPuller;

    /**
     * @var INDataHubPuller
     */
    private $INDataHubPuller;

    public function __construct(DataPuller $dataPuller, INDataHubPuller $INDataHubPuller, string $name = null)
    {
        parent::__construct($name);
        $this->dataPuller = $dataPuller;
        $this->INDataHubPuller = $INDataHubPuller;
    }

    protected function configure()
    {
        $this->addOption('with-datahub', null, InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataHubBackup = $input->getOption('with-datahub');

        if ($dataHubBackup) {
            $output->writeln('Pulling IN Data Hub data');
            $this->INDataHubPuller->pull();
            $output->writeln('IN Data hub data pull completed');
        }

        $output->writeln('Pulling data');
        $data = $this->dataPuller->pull();
        $data2 = $this->dataPuller->pullLTC();
        $data3 = $this->dataPuller->pullUniversal();
        $output->writeln('Data pulled');
        $output->writeln('Validating data');
        if ($this->dataPuller->isValidData($data)) {
            $output->writeln('Data valid');
            $output->writeln('Backing up data to file');
            $this->dataPuller->backupData($data);
            $this->dataPuller->backupLTC($data2);
            $this->dataPuller->backupUniversal($data3);
            $output->writeln('Data backed up to file');
            $output->writeln('Refreshing database');
            $this->dataPuller->refreshDatabase($data);
            $output->writeln('Database refreshed');
        } else {
            $output->writeln('Data validation failed');
        }
        $output->writeln('Process completed');

        return 0;
    }
}