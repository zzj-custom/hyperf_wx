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
namespace App\Task\CompanyWx;

use App\Domain\Logic\CompanyWx\MessageLogic;
use Contract\Exceptions\RemoteException;
use Hyperf\Di\Annotation\Inject;

class SendMessageTask
{
    /**
     * @Inject
     */
    protected MessageLogic $messageLogic;

    /**
     * @throws RemoteException
     */
    public function execute(): void
    {
        $this->messageLogic->sendMessage();
    }
}
