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
use App\Task\Bing\BingImagesTask;
use App\Task\CompanyWx\SendMessageTask;
use App\Task\Word\WordTask;
use App\Task\Word\YellowWordTask;
use Hyperf\Crontab\Crontab;

return [
    'enable' => env('CRONTAB_ENABLE', false),
    'crontab' => [
        (new Crontab())->setName('BingImagesTask')
            ->setRule('0 5 * * *')
            ->setCallback([BingImagesTask::class, 'execute'])
            ->setMemo('执行获取必应图片'),
        (new Crontab())->setName('BeautifulWord')
            ->setRule('*/5 * * * *')
            ->setCallback([WordTask::class, 'execute'])
            ->setMemo('获取每日一词'),
        (new Crontab())->setName('YellowWord')
            ->setRule('*/5 * * * *')
            ->setCallback([YellowWordTask::class, 'execute'])
            ->setMemo('获取黄段子'),
        //        (new Crontab())->setName('CompanyWxSendMessage')
        //            ->setRule('*/2 * * * *')
        //            ->setCallback([SendMessageTask::class, 'execute'])
        //            ->setMemo('企业微信发送每日一句'),
    ],
];
