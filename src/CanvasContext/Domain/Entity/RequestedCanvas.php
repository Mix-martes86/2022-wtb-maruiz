<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Entity;

class RequestedCanvas
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var int
     */
    private int $width;

    /**
     * @var int
     */
    private int $height;

    /**
     * @param string $name
     * @param int $width
     * @param int $height
     */
    public function __construct(string $name, int $width, int $height)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}