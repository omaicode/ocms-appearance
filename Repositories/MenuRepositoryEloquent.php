<?php

namespace Modules\Appearance\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Omaicode\Repository\Eloquent\BaseRepository;
use Omaicode\Repository\Criteria\RequestCriteria;
use Modules\Appearance\Repositories\MenuRepository;
use Modules\Appearance\Entities\Menu;
use Modules\Appearance\Validators\MenuValidator;

/**
 * Class MenuRepositoryEloquent.
 *
 * @package namespace Modules\Appearance\Repositories;
 */
class MenuRepositoryEloquent extends BaseRepository implements MenuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Menu::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * 
     * @param mixed $position 
     * @return Collection 
     * @throws InvalidArgumentException 
     */
    public function getAllWithChilds($position): Collection
    {
        if(Cache::has('menu-'.$position)) {
            return Cache::get('menu-'.$position);
        }

        return Cache::rememberForever('menu-'.$position, function() use ($position) {
            return $this->getModel()
            ->with(['childs' => fn($q) => $q->where('active', true)])
            ->where('active', true)
            ->whereNull('parent_id')
            ->where('position', $position)
            ->orderBy('order', 'ASC')
            ->get();
        });
    }

    public function getRootMenus($position): Collection
    {
        return $this->findWhere(['position' => $position, 'parent_id' => null, 'active' => true]);
    }
}
