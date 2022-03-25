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
return [
    'bing' => [
        'host' => 'https://cn.bing.com/',
        'api' => [
            'crawler_image' => 'HPImageArchive.aspx',
        ],
    ],
    'word' => [
        'host' => 'https://v1.hitokoto.cn',
    ],
    'yellow_word' => [
        'host' => 'https://res.abeim.cn/api-text_wu',
    ],
];
