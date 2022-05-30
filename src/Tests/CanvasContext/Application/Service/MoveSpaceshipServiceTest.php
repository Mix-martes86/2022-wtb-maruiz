<?php
declare(strict_types=1);

namespace App\Tests\CanvasContext\Application\Service;

use App\CanvasContext\Application\Service\MoveSpaceshipService;
use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\RequestedMovement;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;
use App\CanvasContext\Domain\Repository\CacheWriterRepository;
use App\Tests\CanvasContext\Domain\Entity\Aggregate\CanvasMother;
use Faker\Factory as faker;
use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class MoveSpaceshipServiceTest extends TestCase
{
    public function testServiceReturnsMovedShip(): void
    {
        $faker = faker::create();
        $cacheWriter = mocker::mock(CacheWriterRepository::class);
        $canvasName = $faker->word();
        $canvasWidth = $faker->numberBetween(2, 5);
        $canvasHeight = $faker->numberBetween(2, 5);
        $canvasStatus = 'moved';
        $canvasErrors = [];
        $shipX = 0;
        $shipY = 0;
        $movementDirection = $faker->randomElement(['top', 'bottom', 'left', 'right']);

        $expectedCacheResponse = CanvasMother::newCanvasAsArray($canvasName, $canvasWidth, $canvasHeight, $shipX, $shipY);
        $expectedServiceResponse = [
            'code' => Response::HTTP_OK,
            'status' => $canvasStatus,
            'result' => new Canvas(
                new CanvasName($canvasName),
                new CanvasWidth($canvasWidth),
                new CanvasHeight($canvasHeight),
                new Spaceship(
                    new SpaceshipXPosition($shipX),
                    new SpaceshipYPosition($shipY)
                )
            ),
            'errors' => $canvasErrors
        ];

        $cacheWriter->shouldReceive('hasItem')->andReturns(true);
        $cacheWriter->shouldReceive('getItem')->andReturns($expectedCacheResponse);
        $cacheWriter->shouldReceive('delete');
        $cacheWriter->shouldReceive('moveItem')->andReturns($expectedCacheResponse);
        $service = new MoveSpaceshipService($cacheWriter);

        $response = $service->moveSpaceship(new RequestedMovement($canvasName, $movementDirection));

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals($expectedServiceResponse['code'], $response['code']);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals($expectedServiceResponse['status'], $response['status']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertEmpty($response['errors']);
        $this->assertArrayHasKey('result', $response);
        $this->assertEquals(
            $expectedServiceResponse['result']->getName()->getValue(),
            $response['result']->getName()->getValue()
        );
        $this->assertEquals(
            $expectedServiceResponse['result']->getWidth()->getValue(),
            $response['result']->getWidth()->getValue()
        );
        $this->assertEquals(
            $expectedServiceResponse['result']->getHeight()->getValue(),
            $response['result']->getHeight()->getValue()
        );
        $this->assertEquals(
            $expectedServiceResponse['result']->getSpaceship()->getX()->getValue(),
            $response['result']->getSpaceship()->getX()->getValue()
        );
        $this->assertEquals(
            $expectedServiceResponse['result']->getSpaceship()->getY()->getValue(),
            $response['result']->getSpaceship()->getY()->getValue()
        );
    }

    public function testServiceReturnsMissingCanvas(): void
    {
        $faker = faker::create();
        $cacheWriter = mocker::mock(CacheWriterRepository::class);
        $canvasName = $faker->word();
        $canvasStatus = 'not moved';
        $canvasErrors = ['Missing canvas "' . $canvasName . '".'];
        $movementDirection = $faker->randomElement(['top', 'bottom', 'left', 'right']);

        $expectedServiceResponse = [
            'code' => Response::HTTP_BAD_REQUEST,
            'status' => $canvasStatus,
            'result' => null,
            'errors' => $canvasErrors
        ];

        $cacheWriter->shouldReceive('hasItem')->andReturns(false);
        $service = new MoveSpaceshipService($cacheWriter);

        $response = $service->moveSpaceship(new RequestedMovement($canvasName, $movementDirection));

        $this->assertNotEmpty($response);
        $this->assertArrayHasKey('code', $response);
        $this->assertEquals($expectedServiceResponse['code'], $response['code']);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals($expectedServiceResponse['status'], $response['status']);
        $this->assertArrayHasKey('result', $response);
        $this->assertEmpty($response['result']);
        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals($expectedServiceResponse['errors'][0], $response['errors'][0]);
    }
}