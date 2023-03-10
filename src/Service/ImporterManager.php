<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImporterManager extends Importer
{
    private string $import_directory;
    private ExporterManager $exporterManager;
    private OpenAiManager $openAiManager;

    public function __construct(string $import_directory, ExporterManager $exporterManager, OpenAiManager $openAiManager)
    {
        $this->import_directory = $import_directory;
        $this->exporterManager = $exporterManager;
        $this->openAiManager = $openAiManager;
    }

    public function import(): void
    {
        $proteines = [];
        $ingredients = $this->exporterManager->export();

        //fill $proteines thanks to OpenAI API
        $this->getProteines($proteines, $ingredients);

        $date = new \DateTime();
        $xlsxFile = $this->import_directory.'dataIngredients_'.$date->format('d-m-y-s').'.xlsx';

        $spreedSheed = new Spreadsheet();
        $sheet = $spreedSheed->getActiveSheet();
        foreach ($ingredients as $proteine)
        {
            dd($ingredients);
            if(null !== $proteine[0])
            {
                $row = 1;
                $sheet->setCellValue('A'.$row, $proteine[0]);
                $row++;
            }
        }

        $writer = new Xlsx($spreedSheed);
        $writer->save($xlsxFile);
    }

    public function getProteines(array $proteines, array $ingredients) : ?array
    {
        $maxRequest = 40;

        for ($i = 0; count($ingredients) > $i; $i++)
        {
            $data = $this->openAiManager->getDataFromOpenAiApi('Dans ta réponse je veux uniquement retrouver un nombre pas de lettre, donne un nombre en g pour m indiquer combien de gramme de proteine dans 1kg de : '.$ingredients[$i][0].' (récupère l information sur une application sur ), je veux la réponse uniquement pour cette ingredient.Si il n y a pas de proteines répond 0 ');

            if($i == $maxRequest)
            {
                sleep(60);
                $maxRequest += 40;
            }

            try{
                $proteines [] = $data['choices'][0]['text'];
                //$proteines [] = $data['choices'][0]['text'];
            }catch(\Exception $exception)
            {
                $proteines [] = $exception->getMessage();
            }



            if($i == 120)
            {
                dd($proteines);
            }

        }
        return $proteines;
    }
}