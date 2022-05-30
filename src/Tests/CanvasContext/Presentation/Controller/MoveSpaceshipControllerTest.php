<?php
declare(strict_types=1);

namespace App\Tests\CanvasContext\Presentation\Controller;

use App\CanvasContext\Application\Service\MoveSpaceshipService;
use App\CanvasContext\Domain\Entity\Aggregate\Canvas;
use App\CanvasContext\Domain\Entity\Aggregate\Spaceship;
use App\CanvasContext\Domain\Entity\CanvasHeight;
use App\CanvasContext\Domain\Entity\CanvasName;
use App\CanvasContext\Domain\Entity\CanvasWidth;
use App\CanvasContext\Domain\Entity\RequestedMovement;
use App\CanvasContext\Domain\Entity\SpaceshipXPosition;
use App\CanvasContext\Domain\Entity\SpaceshipYPosition;
use App\CanvasContext\Presentation\Adapter\MoveSpaceshipAdapter;
use App\CanvasContext\Presentation\Controller\MoveSpaceshipController;
use App\Tests\CanvasContext\Domain\Entity\Aggregate\CanvasMother;
use Faker\Factory as faker;
use Mockery as mocker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MoveSpaceshipControllerTest extends TestCase
{
    public function testControllerMovesSpaceship(): void
    {
        $faker = faker::create();
        $adapter = mocker::mock(MoveSpaceshipAdapter::class);
        $service = mocker::mock(MoveSpaceshipService::class);
        $canvasName = $faker->word();
        $canvasWidth = $faker->numberBetween(2, 5);
        $canvasHeight = $faker->numberBetween(2, 5);
        $shipX = $faker->numberBetween(0, $canvasWidth);
        $shipY = $faker->numberBetween(0, $canvasHeight);
        $movementDirection = $faker->randomElement(['top', 'bottom', 'left', 'right']);
        $canvasStatus = 'moved';
        $canvasErrors = [];

        $expectedAdapterResponse = new RequestedMovement($canvasName, $movementDirection);
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
        $expectedControllerResponse = new JsonResponse([
            'result' => CanvasMother::newCanvas($canvasName, $canvasWidth, $canvasHeight, $shipX, $shipY),
            'errors' => $canvasErrors,
            'code' => Response::HTTP_OK,
            'status' => $canvasStatus
        ], Response::HTTP_OK);

        $adapter->shouldReceive('getRequestedMovement')->andReturns($expectedAdapterResponse);
        $service->shouldReceive('moveSpaceship')->andReturns($expectedServiceResponse);
        $controller = new MoveSpaceshipController($adapter, $service);

        $response = $controller->patchMove($canvasName, $movementDirection);

        $this->assertNotEmpty($response);
        $this->assertEquals($expectedControllerResponse->getStatusCode(), $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals($expectedControllerResponse->getContent(), $response->getContent());

        $decodedResponse = json_decode($response->getContent());
        $decodedExpectation = json_decode($expectedControllerResponse->getContent());

        $this->assertEmpty($decodedResponse->errors);
        $this->assertEquals($decodedExpectation->status, $decodedResponse->status);
        $this->assertEquals($decodedExpectation->result->name, $decodedResponse->result->name);
        $this->assertEquals($decodedExpectation->result->width, $decodedResponse->result->width);
        $this->assertEquals($decodedExpectation->result->height, $decodedResponse->result->height);
        $this->assertEquals($decodedExpectation->result->spaceship->x, $decodedResponse->result->spaceship->x);
        $this->assertEquals($decodedExpectation->result->spaceship->y, $decodedResponse->result->spaceship->y);
    }

    public function testControllerMissingMovementDirection(): void
    {
        $faker = faker::create();
        $adapter = mocker::mock(MoveSpaceshipAdapter::class);
        $service = mocker::mock(MoveSpaceshipService::class);
        $canvasName = $faker->word();
        $movementDirection = null;
        $canvasStatus = 'not moved';
        $canvasErrors = ['Missing value of an endpoint parameter.'];

        $expectedControllerResponse = new JsonResponse([
            'result' => null,
            'errors' => $canvasErrors,
            'status' => $canvasStatus,
            'code' => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);

        $adapter->shouldReceive('getRequestedMovement')->andReturns(null);
        $service->shouldReceive('moveSpaceship')->andReturns(null);
        $controller = new MoveSpaceshipController($adapter, $service);

        $response = $controller->patchMove($canvasName, $movementDirection);

        $this->assertNotEmpty($response);
        $this->assertEquals($expectedControllerResponse->getStatusCode(), $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals($expectedControllerResponse->getContent(), $response->getContent());

        $decodedResponse = json_decode($response->getContent());
        $decodedExpectation = json_decode($expectedControllerResponse->getContent());

        $this->assertNotEmpty($decodedResponse->errors);
        $this->assertEquals($decodedExpectation->errors[0], $decodedResponse->errors[0]);
        $this->assertEquals($decodedExpectation->status, $decodedResponse->status);
        $this->assertEquals($decodedExpectation->code, $decodedResponse->code);
    }

    public function testControllerMissingCanvasName(): void
    {
        $faker = faker::create();
        $adapter = mocker::mock(MoveSpaceshipAdapter::class);
        $service = mocker::mock(MoveSpaceshipService::class);
        $canvasName = null;
        $movementDirection = $faker->randomElement(['top', 'bottom', 'left', 'right']);
        $canvasStatus = 'not moved';
        $canvasErrors = ['Missing value of an endpoint parameter.'];

        $expectedControllerResponse = new JsonResponse([
            'result' => null,
            'errors' => $canvasErrors,
            'status' => $canvasStatus,
            'code' => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);

        $adapter->shouldReceive('getRequestedMovement')->andReturns(null);
        $service->shouldReceive('moveSpaceship')->andReturns(null);
        $controller = new MoveSpaceshipController($adapter, $service);

        $response = $controller->patchMove($canvasName, $movementDirection);

        $this->assertNotEmpty($response);
        $this->assertEquals($expectedControllerResponse->getStatusCode(), $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals($expectedControllerResponse->getContent(), $response->getContent());

        $decodedResponse = json_decode($response->getContent());
        $decodedExpectation = json_decode($expectedControllerResponse->getContent());

        $this->assertNotEmpty($decodedResponse->errors);
        $this->assertEquals($decodedExpectation->errors[0], $decodedResponse->errors[0]);
        $this->assertEquals($decodedExpectation->status, $decodedResponse->status);
        $this->assertEquals($decodedExpectation->code, $decodedResponse->code);
    }
}