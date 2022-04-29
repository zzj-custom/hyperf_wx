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

namespace App\Model\Ancient;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;

/**
 * Class AncientAuthorModel.
 */
class AncientCommentModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ancient_comment';

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
        'name',
        'author',
        'desc',
        'article_id',
        'url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'author'     => 'string',
        'desc'       => 'text',
        'article_id' => 'integer',
        'url'        => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @param $id
     * @return null|Builder|\Hyperf\Database\Model\Model|object
     */
    public static function getOneDataById($id): Collection
    {
        return self::where('id', '=', $id)->first();
    }
}
