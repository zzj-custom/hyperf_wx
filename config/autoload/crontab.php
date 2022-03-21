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
use Hyperf\Crontab\Crontab;

return [
    'enable' => env('CRONTAB_ENABLE', false),
    'crontab' => [
        (new Crontab())->setName('BingImagesTask')
            ->setRule('*/2 * * * *')
            ->setCallback([App\Application\Task\Bing\BingImagesTask::class, 'execute'])
            ->setMemo('执行获取必应图片'),
    ],
];
