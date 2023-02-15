<?php

namespace Modules\Appearance\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Contracts\AdminPage;
use Modules\Core\Supports\Config;
use Modules\Core\Supports\Media;

class ThemeOptionController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('appearance::messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('appearance::messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ]
        ];           

        $theme = config('appearance.theme');

        return $page
        ->title(__('appearance::messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('appearance::theme-options.general', $theme);
    }

    public function seo(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('appearance::messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('appearance::messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('appearance::messages.seo'), 
                'url'    => route('admin.appearance.theme-options.seo'),
            ],
        ];            
        $theme = config('appearance.theme');

        return $page
        ->title(__('appearance::messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('appearance::theme-options.seo', $theme);
    }

    public function images(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('appearance::messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('appearance::messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('appearance::messages.images'), 
                'url'    => route('admin.appearance.theme-options.images'),
            ],
        ];            
        $theme = config('appearance.theme');

        return $page
        ->title(__('appearance::messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('appearance::theme-options.images', $theme);
    }

    public function socials(AdminPage $page)
    {
        $breadcrumb = [
            [
                'title'  => __('appearance::messages.appearance'), 
                'url'    => '#',
            ],            
            [
                'title'  => __('appearance::messages.theme_options'), 
                'url'    => route('admin.appearance.theme-options'),
            ],
            [
                'title'  => __('appearance::messages.social_links'), 
                'url'    => route('admin.appearance.theme-options.socials'),
            ],
        ];            
        $theme = config('appearance.theme');

        return $page
        ->title(__('appearance::messages.theme_options'))
        ->breadcrumb($breadcrumb)
        ->body('appearance::theme-options.socials', $theme);
    }

    public function update()
    {
        $request_data = $this->request->only([
            'site_name',
            'site_title' ,
            'site_description',
            'site_keywords',
            'seo_title',
            'seo_description',
            'seo_og_image',
            'address',
            'website',
            'email',
            'phone',
            'copyright',
            'logo',
            'logo_light',
            'favicon',
            'page_background',
            'title_background',
            'thumbnail_background',
            'footer_background'   ,
            'facebook',
            'twitter',
            'instagram',
            'youtube',
            'linkedin'  
        ]);
        $data         = [];

        foreach($request_data as $key => $value) {
            $data["appearance__theme__{$key}"] = $value;
        }
        
        $file_keys = ['seo_og_image', 'logo', 'logo_light', 'favicon', 'page_background', 'title_background', 'thumbnail_background', 'footer_background'];

        foreach($file_keys as $key) {
            if($this->request->hasFile($key)) {
                $media = Media::upload($data['appearance__theme__'.$key]);
                $data['appearance__theme__'.$key] = $media ? $media['save_path'] : null;
            } else {
                unset($data['appearance__theme__'.$key]);
            }
        }

        Config::set($data);

        return redirect()->back()->with('toast_success', __('appearance::messages.saved_changes'));
    }
}
