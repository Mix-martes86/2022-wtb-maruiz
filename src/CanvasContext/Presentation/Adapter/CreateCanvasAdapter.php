<?php
declare(strict_types=1);

namespace App\CanvasContext\Presentation\Adapter;

use App\CanvasContext\Domain\Entity\RequestedCanvas;
use App\CanvasContext\Presentation\Request\CreateCanvasRequest;

class CreateCanvasAdapter
{
    /**
     * @param CreateCanvasRequest $newCanvas
     * @return RequestedCanvas
     */
    public function getRequestedCanvas(CreateCanvasRequest $newCanvas): RequestedCanvas
    {
        return new RequestedCanvas(
            $newCanvas->getName(),
            $newCanvas->getHeight(),
            $newCanvas->getWidth()
        );
    }
}