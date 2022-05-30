<?php
declare(strict_types=1);

namespace App\CanvasContext\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCanvasRequest
{
    /**
     * @Assert\NotNull(message="Missing request parameter 'name'.")
     * @var string
     */
    private string $name;

    /**
     * @Assert\NotNull(message="Missing or invalid value of request parameter 'width'.")
     * @var int
     */
    private int $width;

    /**
     * @Assert\NotNull(message="Missing or invalid value of request parameter 'height'.")
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
        $this->name = strtolower($name);
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