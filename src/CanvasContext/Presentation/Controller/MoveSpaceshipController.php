<?php
declare(strict_types=1);

namespace App\CanvasContext\Presentation\Controller;

use App\CanvasContext\Application\Service\MoveSpaceshipService;
use App\CanvasContext\Presentation\Adapter\MoveSpaceshipAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MoveSpaceshipController extends AbstractController
{
    private MoveSpaceshipService $moveSpaceshipService;
    private MoveSpaceshipAdapter $moveSpaceshipAdapter;

    public function __construct(MoveSpaceshipAdapter $moveSpaceshipAdapter, MoveSpaceshipService $moveSpaceshipService) {
        $this->moveSpaceshipAdapter = $moveSpaceshipAdapter;
        $this->moveSpaceshipService = $moveSpaceshipService;
    }

    public function patchMove($canvasName, $movementDirection): Response
    {
        $result = [
            'result' => null,
            'errors' => []
        ];

        if (!$canvasName || !$movementDirection) {
            $result['status'] = 'not moved';
            $result['code'] = Response::HTTP_BAD_REQUEST;
            $result['errors'][] = 'Missing value of an endpoint parameter.';
        }
        else {
            $movementDirectionRequest = $this->moveSpaceshipAdapter->getRequestedMovement($canvasName, $movementDirection);
            $movementDirectionResponse = $this->moveSpaceshipService->moveSpaceship($movementDirectionRequest);

            $result['code'] = $movementDirectionResponse['code'];
            $result['status'] = $movementDirectionResponse['status'];
            $result['result'] = $movementDirectionResponse['result'];
            $result['errors'] = $movementDirectionResponse['errors'];
        }

        return new JsonResponse($result, $result['code']);
    }
}