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
namespace App\Task\Bing;

use App\Domain\Logic\Bing\BingLogic;
use App\Domain\Service\BingService;
use Hyperf\Di\Annotation\Inject;

class BingImagesTask
{
    /**
     * @Inject
     */
    protected BingService $bingService;

    /**
     * @Inject
     */
    protected BingLogic $bingLogic;

    public function execute(): void
    {
//        $this->bingService->schedule('getBingImagesByDay');
        $this->bingLogic->getBingImagesByDay();
    }
}
