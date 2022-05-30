<?php
declare(strict_types=1);

namespace App\CanvasContext\Application\Service;

use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\RequestedMovement;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;
use App\CanvasContext\Domain\Repository\CacheWriterRepository;
use Symfony\Component\HttpFoundation\Response;

class MoveSpaceshipService
{
    private CacheWriterRepository $cacheWriter;

    public function __construct(CacheWriterRepository $cacheWriter)
    {
        $this->cacheWriter = $cacheWriter;
    }

    public function moveSpaceship(RequestedMovement $movementDirectionRequest): array
    {
        $errors = [];
        $result = null;

        if (!$this->cacheWriter->hasItem('canvas_' . $movementDirectionRequest->getCanvasName())) {
            $errors[] = 'Missing canvas "' . $movementDirectionRequest->getCanvasName() . '".';
            $code = Response::HTTP_BAD_REQUEST;
            $status = 'not moved';
        }
        else {
            $canvas = $this->cacheWriter->getItem('canvas_' . $movementDirectionRequest->getCanvasName());

            $spaceship = $this->calculateMovement($canvas, $movementDirectionRequest->getMovementDirection());

            if (!empty($spaceship)) {
                $this->cacheWriter->delete('canvas_' . $movementDirectionRequest->getCanvasName());
                $canvas = $this->cacheWriter->moveItem($canvas, $spaceship);

                $code = Response::HTTP_OK;
                $status = 'moved';
                $result = new Canvas(
                    new CanvasName($canvas['name']),
                    new CanvasWidth($canvas['width']),
                    new CanvasHeight($canvas['height']),
                    new Spaceship(
                        new SpaceshipXPosition($canvas['spaceship']['x']),
                        new SpaceshipYPosition($canvas['spaceship']['y'])
                    )
                );
            }
            else {
                $errors[] = 'Invalid movement direction "' . $movementDirectionRequest->getMovementDirection() . '".';
                $code = Response::HTTP_BAD_REQUEST;
                $status = 'not moved';
            }
        }

        return [
            'code' => $code,
            'status' => $status,
            'result' => $result,
            'errors' => $errors
        ];
    }

    private function calculateMovement(array $canvas, string $movementDirection): array {
        $spaceship = $canvas['spaceship'];
        $newSpaceshipPosition = [];

        switch ($movementDirection) {
            case 'top':
            {
                --$spaceship['y'];
                if ($spaceship['y'] < 0) {
                    $spaceship['y'] = ($canvas['height'] - 1);
                }
                $newSpaceshipPosition = $spaceship;
                break;
            }
            case 'right':
            {
                ++$spaceship['x'];
                if ($spaceship['x'] > ($canvas['width'] - 1)) {
                    $spaceship['x'] = 0;
                }
                $newSpaceshipPosition = $spaceship;
                break;
            }
            case 'bottom':
            {
                ++$spaceship['y'];
                if ($spaceship['y'] > ($canvas['height'] - 1)) {
                    $spaceship['y'] = 0;
                }
                $newSpaceshipPosition = $spaceship;
                break;
            }
            case 'left':
            {
                --$spaceship['x'];
                if ($spaceship['x'] < 0) {
                    $spaceship['x'] = ($canvas['width'] - 1);
                }
                $newSpaceshipPosition = $spaceship;
                break;
            }
        }

        return $newSpaceshipPosition;
    }
}