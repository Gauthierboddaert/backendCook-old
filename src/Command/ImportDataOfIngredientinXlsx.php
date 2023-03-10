<?php

namespace App\Command;

use App\Entity\Ingredient;
use App\Service\ExporterCSV;
use App\Service\ImporterManager;
use App\Service\IngredientManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:export-data-ingredients')]
class ImportDataOfIngredientinXlsx extends Command
{
    private ImporterManager $importerManager;
    private EntityManagerInterface $entityManager;
    private IngredientManager $ingredientManager;
    public function __construct(ImporterManager $importerManager,EntityManagerInterface $entityManager,IngredientManager $ingredientManager, $name = null)
    {
        parent::__construct($name);
        $this->importerManager = $importerManager;
        $this->entityManager = $entityManager;
        $this->ingredientManager = $ingredientManager;
    }

    // the command description shown when running "php bin/console list"
    protected static $defaultDescription = 'Import data ingredients to XLSX file';

    // ...
    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to import data of ingredients')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $answer = $this->importerManager->import();
        //$nbg = $answer['choices'][0]['text'];
        //dd((int)$nbg);

        return Command::SUCCESS;

    }
}