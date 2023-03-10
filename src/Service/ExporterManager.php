<?php

namespace App\Service;

use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Service\FileManager;

class ExporterManager extends Exporter
{
    private string $export_directory;
    private FileManager $fileManager;
    public function __construct(string $export_directory, FileManager $fileManager)
    {
        $this->export_directory = $export_directory;
        $this->fileManager = $fileManager;

    }

    public function export(): ?array
    {
        $data = [];
        $csvFile = $this->export_directory . 'ingredient.xlsx';
        $spreadsheet = IOFactory::load($csvFile);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $row) {
            $lineData = [];

            // Parcours des cellules de la ligne en cours
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            foreach ($cellIterator as $cell) {
                $lineData[] = $cell->getValue();
            }
            $data[] = $lineData;
        }
        array_shift($data);
        return $data;
    }
}