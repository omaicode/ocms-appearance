<?php

namespace Modules\Appearance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Appearance\Enums\MenuPositionEnum;
use Omaicode\Repository\Contracts\Transformable;
use Omaicode\Repository\Traits\TransformableTrait;
use Spatie\Translatable\HasTranslations;

/**
 * Class Menu.
 *
 * @package namespace Modules\Appearance\Entities;
 */
class Menu extends Model implements Transformable
{
    use TransformableTrait, HasTranslations;

    protected $table     = 'appearance_menus';

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'name',
        'parent_id',
        'icon',
        'active',
        'position',
        'order'
    ];


    protected $casts = [
        'active'      => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'position'    => MenuPositionEnum::class
    ];

    protected $appends = [
        'full_url'
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('childs')->orderBy('order', 'ASC');
    }

    public function getFullUrlAttribute()
    {
        if(filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        } else {
            return rtrim(config('app.url', 'http://localhost'), '/').'/'.ltrim($this->url, '/');
        }
    }
}
