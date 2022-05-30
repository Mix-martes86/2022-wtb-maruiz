<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Entity;

abstract class StringValueObject
{
    private String $value;

    /**
     * @param String $value
     */
    public function __construct(String $value)
    {
        $this->value = $value;
    }

    /**
     * @return String
     */
    public function getValue(): String
    {
        return $this->value;
    }

    /**
     * @param String $value
     */
    public function setValue(String $value): void
    {
        $this->value = $value;
    }
}