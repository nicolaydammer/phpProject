<?php
declare(strict_types=1);

namespace App\Application\Actions\ExcelSheetToWebPageConverter;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface;

class UploadExcelSheet extends Action
{
    protected function action(): ResponseInterface
    {
        return $this->respondWithData('hello world');
    }
}