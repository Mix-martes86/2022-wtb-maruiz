<?php
declare(strict_types=1);

namespace App\Tests\CanvasContext\Presentation\Controller;

use App\CanvasContext\Application\Service\CreateCanvasService;
use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\RequestedCanvas;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;
use App\CanvasContext\Presentation\Adapter\CreateCanvasAdapter;
use App\CanvasContext\Presentation\Controller\CreateCanvasController;
use App\CanvasContext\Presentation\Request\CreateCanvasRequest;
use App\Tests\CanvasContext\Domain\Entity\Aggregate\CanvasMother;
use Faker\Factory as faker;
use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreateCanvasControllerTest extends TestCase
{
    public function testControllerReturnsNewCanvas(): void
    {
        $faker = faker::create();
        $adapter = mocker::mock(CreateCanvasAdapter::class);
        $service = mocker::mock(CreateCanvasService::class);
        $canvasName = $faker->word();
        $canvasWidth = $faker->numberBetween(2, 5);
        $canvasHeight = $faker->numberBetween(2, 5);
        $canvasStatus = 'created';
        $canvasErrors = [];

        $expectedAdapterResponse = new RequestedCanvas($canvasName, $canvasWidth, $canvasHeight);
        $expectedServiceResponse = [
            'code' => Response::HTTP_CREATED,
            'status' => $canvasStatus,
            'result' => new Canvas(
                new CanvasName($canvasName),
                new CanvasWidth($canvasWidth),
                new CanvasHeight($canvasHeight),
                new Spaceship(
                    new SpaceshipXPosition(0),
                    new SpaceshipYPosition(0)
                )
            ),
            'errors' => $canvasErrors
        ];
        $expectedControllerResponse = new JsonResponse([
            'status' => $canvasStatus,
            'canvas' => CanvasMother::newEmptyCanvas($canvasName, $canvasWidth, $canvasHeight),
            'errors' => $canvasErrors
        ], Response::HTTP_CREATED);

        $adapter->shouldReceive('getRequestedCanvas')->andReturns($expectedAdapterResponse);
        $service->shouldReceive('createCanvas')->andReturns($expectedServiceResponse);
        $newCanvas = new CreateCanvasRequest($canvasName, $canvasWidth, $canvasHeight);
        $controller = new CreateCanvasController($adapter, $service);

        $response = $controller->postCreateCanvas($newCanvas);

        $this->assertNotEmpty($response);
        $this->assertEquals($expectedControllerResponse->getStatusCode(), $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals($expectedControllerResponse->getContent(), $response->getContent());

        $decodedResponse = json_decode($response->getContent());
        $decodedExpectation = json_decode($expectedControllerResponse->getContent());

        $this->assertEmpty($decodedResponse->errors);
        $this->assertEquals($decodedExpectation->status, $decodedResponse->status);
        $this->assertEquals($decodedExpectation->canvas->name, $decodedResponse->canvas->name);
        $this->assertEquals($decodedExpectation->canvas->width, $decodedResponse->canvas->width);
        $this->assertEquals($decodedExpectation->canvas->height, $decodedResponse->canvas->height);
        $this->assertEquals($decodedExpectation->canvas->spaceship->x, $decodedResponse->canvas->spaceship->x);
        $this->assertEquals($decodedExpectation->canvas->spaceship->y, $decodedResponse->canvas->spaceship->y);
    }
}