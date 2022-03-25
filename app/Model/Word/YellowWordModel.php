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
namespace App\Model\Word;

use App\Model\Model;

class YellowWordModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yellow_word';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'default';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'md5_txt',
        'text',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'md5_txt' => 'string',
        'text' => 'string',
    ];

    /**
     * @param array $md5
     * @return array
     */
    public static function getOneDataByMd5(array $md5): array
    {
        $list = self::whereIn('md5_txt', $md5)->pluck('md5_txt');
        return empty($list) ? [] : $list->toArray();
    }
}
