<?php
declare(strict_types=1);

namespace App\Application\Actions\ExcelSheetToWebPageConverter;

use App\Application\Actions\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class ExcelSheetToWebPageConverter extends Action
{
    private Twig $twig;

    //inject twig
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    protected function action(): ResponseInterface
    {
        //get the temporary excel sheet name where the file is saved
        $fileName = $_FILES['excelSheet']['tmp_name'];

        try {
            //load file into reader
            //also checks if it really is a sheet and contains proper data
            $reader = IOFactory::load($fileName);
            //get all sheet names
            $sheetNames = $reader->getSheetNames();
            //array to save all the data
            $sheetDataArray = [];
            //get data by sheet name
            foreach ($sheetNames as $name) {
                //get sheet by name
                $sheet = $reader->getSheetByName($name);
                //foreach through the rows
                foreach ($sheet->getRowIterator() as $row) {
                    //create cell iterator where we get data from
                    $cellIterator = $row->getCellIterator();
                    //we want empty places so we dont have to generate them later
                    $cellIterator->setIterateOnlyExistingCells(false);
                    //temporary place to store all the cells from a row
                    $cells = [];
                    //foreach through the cells
                    foreach ($cellIterator as $cell) {
                        //save the cells in the array so we have row data
                        $cells[] = $cell->getValue();
                    }
                    //put the cells in a row
                    $sheetDataArray[$name][] = $cells;
                }
            }
        } catch (\InvalidArgumentException $e) {
            //todo: not working, excel code doing its own thing
            return $this->respondWithData(['error' => 'Something went wrong when processing your file']);
        }
        //create render with twig
        return $this->twig->render($this->response, 'ExcelSheetToWebPageConverter/excelReader.twig', ['sheetNames' => $sheetNames, 'sheetData' => $sheetDataArray]);
    }
}