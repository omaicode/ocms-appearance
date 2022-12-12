<?php

namespace Modules\Appearance\Http\Controllers\Admin;

use ApiResponse;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Appearance\Enums\MenuPositionEnum;
use Modules\Appearance\Http\Requests\Admin\StoreMenuRequest;
use Modules\Appearance\Http\Requests\Admin\UpdateMenuOrderRequest;
use Modules\Appearance\Repositories\MenuRepository;
use Modules\Core\Contracts\AdminPage;
use Throwable;

class MenuController extends Controller
{
    /**
     * 
     * @var Request
     */
    protected Request $request;

    /**
     * 
     * @var MenuRepository
     */
    protected MenuRepository $repository;
    
    /**
     * 
     * @var array
     */
    protected array $breadcrumb;

    /**
     * 
     * @var AdminPage
     */
    protected AdminPage $page;

    public function __construct(Request $request, MenuRepository $repository, AdminPage $page)
    {
        $this->request    = $request;
        $this->repository = $repository;
        $this->page       = $page;
        $this->breadcrumb = [
            [
                'title'  => __('appearance::messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('appearance::messages.menus'), 
                'url'    => route('admin.appearance.menus.index'),
            ]
        ];  
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $position = $this->request->get('position', MenuPositionEnum::PRIMARY_MENU);
        $menus = $this->repository->getAllWithChilds($position);
        $root_menus = $this->repository->getRootMenus($position);

        return $this->page
        ->push("scripts", "appearance::menu.scripts")
        ->title(__('appearance::messages.menus'))    
        ->breadcrumb($this->breadcrumb)    
        ->body('appearance::menu.index', compact('menus', 'root_menus', 'position'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreMenuRequest $request)
    {
        $menu = null;
        $data = $request->only([
            'position',
            'name',
            'url',
            'icon',
            'active',
            'parent_id',
            'order'
        ]);

        try {
            DB::beginTransaction();
            if($request->filled('menu_id')) {
                $menu = $this->repository->findByField('id', $request->menu_id);
            }

            if(isset($data['active']) && $data['active'] == 'on') {
                $data['active'] = true;
            } else {
                $data['active'] = false;
            }

            if(!blank($menu)) {
                $locale       = $request->getLocale();
                if($locale == 'en') {
                    $data['name'] = ['en' => $data['name'], 'vi' => $menu->first()->getTranslation('name', 'vi')];
                } else {
                    $data['name'] = ['vi' => $data['name'], 'en' => $menu->first()->getTranslation('name', 'vi')];
                }
            } else {
                $data['name'] = ['en' => $data['name'], 'vi' => $data['name']];
            }

            $data['url']  = strlen($data['url']) > 1 ? ltrim($data['url'], '/') : $data['url'];
            
            if(!blank($menu)) {
                $menu->first()->update($data);
            } else {
                $this->repository->create($data);
            }
            
            Cache::forget('menu-'.$data['position']);
            DB::commit();
            return redirect()
            ->route('admin.appearance.menus.index', ['position' => $request->position])
            ->with('toast_success', __('appearance::messages.saved_changes'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->back()->with('toast_error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id = null)
    {
        if($id) {
            $menu = $this->repository->find($id);
            if($menu) {
                Cache::forget('menu-'.$menu->position);
                $menu->delete();
            }

            return ApiResponse::success("Deleted menu item");
        }

        return ApiResponse::error("Menu item not found");
    }

    public function updateOrder(UpdateMenuOrderRequest $request)
    {
        $parent = $request->parent_id ?: null;
        foreach($request->ordered_list as $order => $id) {
            $this->repository->find($id)->update([
                'order' => $order, 
                'parent_id' => $parent
            ]);
        }

        return ApiResponse::success();
    }
}
