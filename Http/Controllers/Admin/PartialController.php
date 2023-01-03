<?php

namespace Modules\Appearance\Http\Controllers\Admin;

use AdminAsset;
use ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Core\Contracts\AdminPage;
use Theme;

class PartialController extends Controller
{
    protected Request $request;
    protected AdminPage $page;

    public function __construct(Request $request, AdminPage $page)
    {
        $this->request = $request;
        $this->page    = $page;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        AdminAsset::addScript("partial-editor", asset('modules/appearance/js/partial-editor.js'));
        AdminAsset::addStyle("partial-editor", asset('modules/appearance/css/partial-editor.css'));

        $breadcrumb = [
            [
                'title'  => __('appearance::messages.partials'), 
                'url'    => route('admin.appearance.partials.index'),
            ]           
        ];

        return $this->page
        ->breadcrumb($breadcrumb)
        ->title(__('appearance::messages.partials'))
        ->body('appearance::partials')
        ->addVariables(['apiURL' => route('admin.appearance.partials.index')]);
    }

    /**
     * Get tree folder & partials
     * @return ApiResponse 
     */
    public function getTree()
    {
        $theme      = Theme::current();
        $theme_path = $theme->getPath();
        $partials_path = rtrim($theme_path, '/')."/views/partials";

        if(!File::isDirectory($partials_path)) {
            File::ensureDirectoryExists($partials_path);
        }

        $partials = $this->getPartialsTree([], $partials_path);
        return ApiResponse::success()->data($partials);
    }
    
    private function getPartialsTree(array $partials, $path)
    {
        $tree        = $partials ?: [];
        $directories = File::directories($path);
        $files       = File::files($path);

        if(count($directories) > 0) {
            foreach($directories as $directory) {
                $dir_splited = explode('/', $directory);

                $tree[] = [
                    'name'      => $dir_splited[count($dir_splited) - 1],
                    'is_dir'    => true,
                    'is_open'   => true,
                    'path'      => $directory,
                    'childrens' => $this->getPartialsTree([], $directory) ?: []
                ];
            }
        }

        if(count($files) > 0) {
            foreach($files as $file) {
                if(strpos($file->getBasename(), '.blade.php') !== false) {
                    $tree[] = [
                        'name'     => $file->getBasename('.blade.php'),
                        'basename' => $file->getBasename(),
                        'is_dir'   => false,
                        'is_open'  => false,
                        'path'     => rtrim($file->getPath(), '/')
                    ];
                }
            }
        }

        return $tree;
    }

    private function validPath($path)
    {
        $theme = Theme::current();
        $theme_paths = explode('/', $theme->getPath());
        $paths = explode('/', $path);
        
        foreach($theme_paths as $path_name) {
            if(!in_array($path_name, $paths)) {
                return false;
            }
        }

        return true;
    }

    public function getContent(Request $request)
    {
        $content = '';

        if($request->filled('path') && File::exists($request->path) && $this->validPath($request->path)) {
            $content = File::get($request->path);
        }

        return ApiResponse::success()->data(compact('content'));
    }

    public function saveContent(Request $request)
    {
        if($request->filled('path') && File::exists($request->path) && $this->validPath($request->path)) {
            $content = $request->get('content', '');
            File::put($request->path, $content);
        }

        return ApiResponse::success();
    }

    public function destroy(Request $request)
    {
        if($request->filled('path') && File::exists($request->path) && $this->validPath($request->path)) {
            File::delete($request->path);
        }

        return ApiResponse::success();
    }
}
