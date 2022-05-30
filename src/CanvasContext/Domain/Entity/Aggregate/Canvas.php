<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Entity\Aggregate;

use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use JsonSerializable;

class Canvas implements JsonSerializable
{
    private CanvasName $name;
    private CanvasWidth $width;
    private CanvasHeight $height;
    private Spaceship $spaceship;

    /**
     * @param CanvasName $name
     * @param CanvasWidth $width
     * @param CanvasHeight $height
     * @param Spaceship $spaceship
     */
    public function __construct(CanvasName $name, CanvasWidth $width, CanvasHeight $height, Spaceship $spaceship)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->spaceship = $spaceship;
    }

    /**
     * @return CanvasName
     */
    public function getName(): CanvasName
    {
        return $this->name;
    }

    /**
     * @param CanvasName $name
     */
    public function setName(CanvasName $name): void
    {
        $this->name = $name;
    }

    /**
     * @return CanvasWidth
     */
    public function getWidth(): CanvasWidth
    {
        return $this->width;
    }

    /**
     * @param CanvasWidth $width
     */
    public function setWidth(CanvasWidth $width): void
    {
        $this->width = $width;
    }

    /**
     * @return CanvasHeight
     */
    public function getHeight(): CanvasHeight
    {
        return $this->height;
    }

    /**
     * @param CanvasHeight $height
     */
    public function setHeight(CanvasHeight $height): void
    {
        $this->height = $height;
    }

    /**
     * @return Spaceship
     */
    public function getSpaceship(): Spaceship
    {
        return $this->spaceship;
    }

    /**
     * @param Spaceship $spaceship
     */
    public function setSpaceship(Spaceship $spaceship): void
    {
        $this->spaceship = $spaceship;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->getValue(),
            'width' => $this->width->getValue(),
            'height' => $this->height->getValue(),
            'spaceship' => [
                'x' => $this->spaceship->getX()->getValue(),
                'y' => $this->spaceship->getY()->getValue(),
            ]
        ];
    }
}