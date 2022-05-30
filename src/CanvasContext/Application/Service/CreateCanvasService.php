<?php
declare(strict_types=1);

namespace App\CanvasContext\Application\Service;

use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\RequestedCanvas;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;
use App\CanvasContext\Domain\Repository\CacheWriterRepository;
use Symfony\Component\HttpFoundation\Response;

class CreateCanvasService
{
    private CacheWriterRepository $cacheWriter;

    public function __construct(CacheWriterRepository $cacheWriter)
    {
        $this->cacheWriter = $cacheWriter;
    }

    public function createCanvas(RequestedCanvas $newCanvasRequest): array
    {
        $this->cacheWriter->delete('canvas_' . $newCanvasRequest->getName());
        $canvas = $this->cacheWriter->createItem($newCanvasRequest->getName(), $newCanvasRequest->getWidth(), $newCanvasRequest->getHeight());

        return [
            'code' => Response::HTTP_CREATED,
            'status' => 'created',
            'result' => new Canvas(
                new CanvasName($canvas['name']),
                new CanvasWidth($canvas['width']),
                new CanvasHeight($canvas['height']),
                new Spaceship(
                    new SpaceshipXPosition($canvas['spaceship']['x']),
                    new SpaceshipYPosition($canvas['spaceship']['y'])
                )
            ),
            'errors' => []
        ];
    }
}