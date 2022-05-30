<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Entity\Aggregate;

use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;

class Spaceship
{
    private SpaceshipXPosition $x;
    private SpaceshipYPosition $y;

    /**
     * @param \App\CanvasContext\Domain\Entity\SpaceshipXPosition $x
     * @param SpaceshipYPosition $y
     */
    public function __construct(SpaceshipXPosition $x, SpaceshipYPosition $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return SpaceshipXPosition
     */
    public function getX(): SpaceshipXPosition
    {
        return $this->x;
    }

    /**
     * @param SpaceshipXPosition $x
     */
    public function setX(SpaceshipXPosition $x): void
    {
        $this->x = $x;
    }

    /**
     * @return SpaceshipYPosition
     */
    public function getY(): SpaceshipYPosition
    {
        return $this->y;
    }

    /**
     * @param \App\CanvasContext\Domain\Entity\SpaceshipYPosition $y
     */
    public function setY(SpaceshipYPosition $y): void
    {
        $this->y = $y;
    }
}