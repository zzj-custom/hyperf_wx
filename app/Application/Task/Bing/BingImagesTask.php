<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Application\Task\Bing;

use App\Domain\Service\BingService;
use Hyperf\Di\Annotation\Inject;

class BingImagesTask
{
    /**
     * @Inject
     */
    protected BingService $bingService;

    public function execute(): void
    {
        $this->bingService->schedule('getBingImagesByDay');
    }
}
