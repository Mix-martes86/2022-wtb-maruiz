<?php
declare(strict_types=1);

namespace App\CanvasContext\Presentation\Adapter;

use App\CanvasContext\Domain\Entity\RequestedMovement;

class MoveSpaceshipAdapter
{
    /**
     * @param string $canvasName
     * @param string $movementDirection
     * @return RequestedMovement
     */
    public function getRequestedMovement(string $canvasName, string $movementDirection): RequestedMovement
    {
        return new RequestedMovement(
            $canvasName,
            $movementDirection
        );
    }
}