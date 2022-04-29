<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Domain\Logic\CompanyWx;

use App\Infrastructure\Service\CompanyWxApiClient\CompanyWxApiService;
use App\Model\Word\BeautifulWordModel;
use Hyperf\Di\Annotation\Inject;

class MessageLogic
{
    /**
     * @Inject
     */
    protected CompanyWxApiService $companyWxApiService;

    public function getToken(): string
    {
        return $this->companyWxApiService->getAccessToken();
    }

    public function sendMessage()
    {
        //获取当前表所有的数据
        $count = BeautifulWordModel::count();

        //获取随机数据
        $list = BeautifulWordModel::getOneDataById(rand(1, $count));

        $this->companyWxApiService->sendUserMessage($list->hitokoto);
    }
}
