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
        'host' => 'https://cn.bing.com',
        'api' => [
            'crawler_image' => 'HPImageArchive.aspx',
        ],
        'all_host' => 'https://bing.ioliu.cn',
    ],
    'word' => [
        'host' => 'https://v1.hitokoto.cn',
    ],
    'yellow_word' => [
        'host' => 'https://res.abeim.cn/api-text_wu',
    ],
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36',
];
