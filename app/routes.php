<?php
declare(strict_types=1);

use App\Application\Actions\ExcelSheetToWebPageConverter\ExcelSheetToWebPageConverter;
use App\Application\Actions\ExcelSheetToWebPageConverter\UploadExcelSheet;
use Slim\App;

//return when called on the file
return function (App $app) {
    //get homepage of the converter
    $app->get('/', UploadExcelSheet::class);
    //upload the excel sheet and convert it to web page
    $app->post('/', ExcelSheetToWebPageConverter::class);
};