<?php
declare(strict_types=1);

namespace App\CanvasContext\Infrastructure;

use App\CanvasContext\Domain\Repository\CacheWriterRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheWriter implements CacheWriterRepository
{
    /** @var CacheInterface|FilesystemAdapter $cache */
    private CacheInterface $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function delete(string $name): void
    {
        $this->cache->delete('canvas_' . $name);
    }

    public function createItem(string $canvasName, int $canvasWidth, int $canvasHeight): array
    {
        return $this->cache->get('canvas_' . $canvasName, function (ItemInterface $item) use (
            $canvasName,
            $canvasWidth,
            $canvasHeight
        ) {
            return [
                'name' => $canvasName,
                'width' => $canvasWidth,
                'height' => $canvasHeight,
                'spaceship' => [
                    'x' => 0,
                    'y' => 0,
                ]
            ];
        });
    }

    public function moveItem(array $canvas, array $spaceship): array
    {
        return $this->cache->get('canvas_' . $canvas['name'], function (ItemInterface $item) use ($canvas, $spaceship) {
            return [
                'name' => $canvas['name'],
                'width' => $canvas['width'],
                'height' => $canvas['height'],
                'spaceship' => $spaceship
            ];
        });
    }

    public function hasItem(string $canvasName): bool
    {
        return $this->cache->hasItem('canvas_' . $canvasName);
    }

    public function getItem(string $canvasName): array
    {
        return $this->cache->getItem('canvas_' . $canvasName)->get();
    }
}