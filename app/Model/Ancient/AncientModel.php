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

/**
 * Class BingImagesModel.
 * * @method static Builder|static filterByAncient(string $name)
 */
class AncientModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ancient';

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
        'ancient_name',
        'ancient_type_id',
        'ancient_type_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'ancient_id'        => 'string',
        'ancient_name'      => 'string',
        'ancient_type_id'   => 'string',
        'ancient_type_name' => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function scopeFilterByAncient(Builder $query, string $ancientTypeName): Builder
    {
        return $query->where('ancient_type_name', '=', $ancientTypeName);
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
