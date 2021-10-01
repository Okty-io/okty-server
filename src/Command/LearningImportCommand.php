<?php

namespace App\Command;

use App\Service\Learning\Import as LearningImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class LearningImportCommand extends Command
{
    private LearningImport $learningImport;

    protected static $defaultName = 'learning:import';

    public function __construct(LearningImport $learningImport)
    {
        parent::__construct(null);

        $this->learningImport = $learningImport;
    }

    protected function configure(): void
    {
        $this->setDescription('Import learning');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->learningImport->import();
    }
}
