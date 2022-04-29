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
namespace App\Model\Ancient;

use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;

/**
 * Class AncientAuthorModel.
 * @method static Builder|static filterByAncientArticle(string $name, string $categoryName)
 */
class AncientArticleModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ancient_article';

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
        'ancient_id',
        'ancient_type_id',
        'name',
        'content',
        'category_name',
        'category_desc',
        'author_id',
        'video_url',
        'collection',
        'view',
        'comment_num',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                  => 'integer',
        'ancient_id'          => 'integer',
        'ancient_type_id'     => 'integer',
        'name'                => 'string',
        'content'             => 'text',
        'category_name'       => 'string',
        'category_desc'       => 'text',
        'author_id'           => 'integer',
        'video_url'           => 'string',
        'collection'          => 'integer',
        'view'                => 'integer',
        'comment_num'         => 'integer',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    public function scopeFilterByAncientArticle(Builder $query, string $name, string $categoryName): Builder
    {
        return $query->where('name', '=', $name)
            ->where('category_name', '=', $categoryName);
    }

    /**
     * @param $id
     * @return null|Builder|\Hyperf\Database\Model\Model|object
     */
    public static function getOneDataById($id): Collection
    {
        return self::where('id', '=', $id)->first();
    }
}
