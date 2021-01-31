<?php
declare(strict_types=1);

namespace App\Application\Actions\ExcelSheetToWebPageConverter;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class UploadExcelSheet extends Action
{
    private Twig $twig;

    //inject twig
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    protected function action(): ResponseInterface
    {
        //create render with twig
        $render = $this->twig->render($this->response,'ExcelSheetToWebPageConverter/excelUpload.twig', []);
        return $this->respondWithData($render);
    }
}