<?php
declare(strict_types=1);

namespace App\Tests\CanvasContext\Domain\Entity\Aggregate;

use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;

class CanvasMother
{
    public static function newEmptyCanvas(string $name, int $width, int $height): Canvas {
        return CanvasMother::newCanvas($name, $width, $height, 0, 0);
    }

    public static function newCanvas(string $name, int $width, int $height, int $shipX, int $shipY): Canvas {
        return new Canvas(
            new CanvasName($name),
            new CanvasWidth($width),
            new CanvasHeight($height),
            new Spaceship(
                new SpaceshipXPosition($shipX),
                new SpaceshipYPosition($shipY)
            )
        );
    }

    public static function newCanvasAsArray(string $name, int $width, int $height, int $shipX, int $shipY): array {
        return [
            'name' => $name,
            'width' => $width,
            'height' => $height,
            'spaceship' => [
                'x' => $shipX,
                'y' => $shipY
            ]
        ];
    }
}