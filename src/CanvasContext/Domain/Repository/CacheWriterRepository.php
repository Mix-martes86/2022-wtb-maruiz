<?php
declare(strict_types=1);

namespace App\CanvasContext\Domain\Repository;

interface CacheWriterRepository
{
    public function delete(string $name);
    public function createItem(string $canvasName, int $canvasWidth, int $canvasHeight): array;
    public function moveItem(array $canvas, array $spaceship): array;
    public function hasItem(string $canvasName): bool;
    public function getItem(string $canvasName): array;
}