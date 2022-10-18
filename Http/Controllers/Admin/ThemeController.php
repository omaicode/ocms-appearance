<?php

namespace Modules\Appearance\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Contracts\AdminPage;
use Modules\Core\Supports\Config;
use Theme;

class ThemeController extends Controller
{
    protected $request;
    protected $adminPage;

    public function __construct(Request $request, AdminPage $adminPage)
    {
        $this->request   = $request;
        $this->adminPage = $adminPage;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $themes = Theme::all();
        $current_theme = Theme::current();

        $breadcrumb = [
            [
                'title'  => __('appearance::messages.themes'), 
                'url'    => route('admin.appearance.themes'),
            ]
        ];           

        return $this->adminPage
        ->title('appearance::messages.themes')
        ->breadcrumb($breadcrumb)
        ->body('appearance::themes', compact('themes', 'current_theme'));
    }

    public function setTheme($theme)
    {
        $theme = Theme::has($theme);

        if(!$theme) {
            return redirect()->route('admin.appearance.themes')->with('toast_error', __('appearance::messages.theme_not_exists'));
        }

        Config::set('appearance_currentTheme', $theme);
        Theme::set($theme);

        return redirect()->route('admin.appearance.themes')->with('toast_success', __('appearance::messages.changed_theme_success'));
    }
}
