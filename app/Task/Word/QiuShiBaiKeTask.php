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
namespace App\Task\Word;

use App\Domain\Logic\Word\WordLogic;
use Contract\Exceptions\RemoteException;
use Hyperf\Di\Annotation\Inject;

class QiuShiBaiKeTask
{
    /**
     * @Inject
     */
    protected WordLogic $wordLogic;

    /**
     * @throws RemoteException
     */
    public function execute(): void
    {
        $this->wordLogic->handleQiuShiBaiKe('latest');
        $this->wordLogic->handleQiuShiBaiKe('text');
        $this->wordLogic->handleQiuShiBaiKe('image');
        $this->wordLogic->handleQiuShiBaiKe('video');
    }
}