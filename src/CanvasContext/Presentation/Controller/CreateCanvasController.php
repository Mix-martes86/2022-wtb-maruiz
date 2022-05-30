<?php
declare(strict_types=1);

namespace App\CanvasContext\Presentation\Controller;

use App\CanvasContext\Application\Service\CreateCanvasService;
use App\CanvasContext\Presentation\Adapter\CreateCanvasAdapter;
use App\CanvasContext\Presentation\Request\CreateCanvasRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateCanvasController extends AbstractController
{
    private CreateCanvasService $createCanvasService;
    private CreateCanvasAdapter $createCanvasAdapter;

    public function __construct(CreateCanvasAdapter $createCanvasAdapter, CreateCanvasService $createCanvasService) {
        $this->createCanvasAdapter = $createCanvasAdapter;
        $this->createCanvasService = $createCanvasService;
    }

    /**
     * @ParamConverter("newCanvas", converter="fos_rest.request_body")
     */
    public function postCreateCanvas(CreateCanvasRequest $newCanvas): Response
    {
        $newCanvasRequest = $this->createCanvasAdapter->getRequestedCanvas($newCanvas);
        $newCanvasResponse = $this->createCanvasService->createCanvas($newCanvasRequest);

        return new JsonResponse([
            'status' => $newCanvasResponse['status'],
            'canvas' => $newCanvasResponse['result'],
            'errors' => $newCanvasResponse['errors']
        ], $newCanvasResponse['code']);
    }
}