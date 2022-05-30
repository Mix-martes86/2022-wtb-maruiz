<?php
declare(strict_types=1);

namespace App\Tests\CanvasContext\Infrastructure;

use App\CanvasContext\Application\Service\MoveSpaceshipService;
use App\CanvasContext\Infrastructure\CacheWriter;
use App\Tests\CanvasContext\Domain\Entity\Aggregate\CanvasMother;
use Faker\Factory as faker;
use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;

class CacheWriterTest extends TestCase
{
    public function testWriterReturnsNewCanvas(): void
    {
        $faker = faker::create();
        //$fsAdapter = mocker::mock(FilesystemAdapter::class);
        $canvasName = $faker->word();
        $canvasWidth = $faker->numberBetween(2, 5);
        $canvasHeight = $faker->numberBetween(2, 5);
        $shipX = 0;
        $shipY = 0;

        $expectedCacheResponse = CanvasMother::newCanvasAsArray($canvasName, $canvasWidth, $canvasHeight, $shipX, $shipY);

        /*$fsAdapter->shouldReceive('delete');
        $fsAdapter->shouldReceive('get')->andReturns($expectedCacheResponse);
        $fsAdapter->shouldReceive('hasItem')->andReturns(true);
        $fsAdapter->shouldReceive('getItem')->andReturns($expectedCacheResponse);*/
        $cacheWriter = new CacheWriter();

        $response = $cacheWriter->createItem($canvasName, $canvasWidth, $canvasHeight);

        $this->assertNotEmpty($response);
        $this->assertCanvasResults($response, $expectedCacheResponse);
    }

    public function testWriterReturnsMovedCanvas(): void
    {
        $faker = faker::create();
        $canvasName = $faker->word();
        $canvasWidth = $faker->numberBetween(2, 5);
        $canvasHeight = $faker->numberBetween(2, 5);
        $shipX = 0;
        $shipY = 0;

        $expectedCacheResponse = CanvasMother::newCanvasAsArray($canvasName, $canvasWidth, $canvasHeight, $shipX, $shipY);

        $cacheWriter = new CacheWriter();

        $response = $cacheWriter->moveItem($expectedCacheResponse, $expectedCacheResponse['spaceship']);

        $this->assertNotEmpty($response);
        $this->assertCanvasResults($response, $expectedCacheResponse);
    }

    public function testWriterReturnsCanvasNonExistent(): void
    {
        $faker = faker::create();
        $canvasName = $faker->word();

        $cacheWriter = new CacheWriter();

        $response = $cacheWriter->hasItem($canvasName);

        $this->assertFalse($response);
    }

    /** Private function to assert if the canvas received is correct or not.
     *
     * @param array $cachedArray
     * @param array $expectedArray
     * @return void
     */
    private function assertCanvasResults(array $cachedArray, array $expectedArray): void
    {
        $this->assertArrayHasKey('name', $cachedArray);
        $this->assertEquals($expectedArray['name'], $cachedArray['name']);
        $this->assertArrayHasKey('width', $cachedArray);
        $this->assertEquals($expectedArray['width'], $cachedArray['width']);
        $this->assertArrayHasKey('height', $cachedArray);
        $this->assertEquals($expectedArray['height'], $cachedArray['height']);
        $this->assertArrayHasKey('spaceship', $cachedArray);
        $this->assertArrayHasKey('x', $cachedArray['spaceship']);
        $this->assertEquals($expectedArray['spaceship']['x'], $cachedArray['spaceship']['x']);
        $this->assertArrayHasKey('y', $cachedArray['spaceship']);
        $this->assertEquals($expectedArray['spaceship']['y'], $cachedArray['spaceship']['y']);
    }
}