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

namespace App\Model\Bing;

use App\Model\Model;
use Hyperf\Database\Model\Builder;

/**
 * Class BingImagesModel.
 * * @method static Builder|static filterByBingImages(string $name)
 */
class BingImagesModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bing_images';

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
        'images_url',
        'name',
        'date',
        'click_count',
        'download_count',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'images_url'     => 'string',
        'name'           => 'string',
        'date'           => 'date',
        'click_count'    => 'integer',
        'download_count' => 'integer',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function scopeFilterByBingImages(Builder $query, string $name): Builder
    {
        return $query->where('name', '=', $name);
    }

    /**
     * @param $id
     * @return null|Builder|\Hyperf\Database\Model\Model|object
     */
    public static function getOneDataById($id)
    {
        return self::where('id', '=', $id)->first();
    }
}
