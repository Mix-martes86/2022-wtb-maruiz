<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Entity;

class RequestedMovement
{
    /**
     * @var string
     */
    private string $canvasName;

    /**
     * @var string
     */
    private string $movementDirection;

    /**
     * @param string $canvasName
     * @param string $movementDirection
     */
    public function __construct(string $canvasName, string $movementDirection)
    {
        $this->canvasName = $canvasName;
        $this->movementDirection = $movementDirection;
    }

    /**
     * @return string
     */
    public function getCanvasName(): string
    {
        return $this->canvasName;
    }

    /**
     * @return string
     */
    public function getMovementDirection(): string
    {
        return $this->movementDirection;
    }
}