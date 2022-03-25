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
use Hyperf\Database\Model\Builder;

class BeautifulWordModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beautiful_word';

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
        'hitokoto',
        'type',
        'from',
        'from_who',
        'commit_from',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'hitokoto' => 'string',
        'type' => 'string',
        'from' => 'string',
        'from_who' => 'string',
        'commit_from' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @param $id
     * @return null|Builder|\Hyperf\Database\Model\Model|object
     */
    public static function getOneDataById($id)
    {
        return self::where('id', '=', $id)->first();
    }
}
