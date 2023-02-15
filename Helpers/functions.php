<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Modules\Appearance\Enums\MenuPositionEnum;
use Modules\Appearance\Repositories\MenuRepository;

if (!function_exists('page_title')) {
	/**
	 * Get formatted page title
	 *
	 * @param  bool  $with_app_name
	 * @param  string  $separator
	 * @return string
	 */
	function page_title(string $title, bool $withAppName = true, $separator = '-', $invert = false): string
	{
		if (View::hasSection('title')) {
			$title = View::getSection('title');
		}

		if (!empty($title) && $withAppName) {
			if ($invert) {
				return $title . " " . trim(e($separator)) . " " . config('app.name');
			} else {
				return config('app.name') . " " . trim(e($separator)) . " " . $title;
			}
		} else {
			return config('app.name');
		}
	}
}

if (!function_exists('theme')) {
	/**
	 * Set theme.
	 *
	 * @param  string  $themeName
	 * @return \Modules\Appearance\Theme
	 */
	function theme($themeName = null)
	{
		if ($themeName) {
			\Theme::set($themeName);
		}

		return \Theme::current();
	}
}

if (!function_exists('theme_asset')) {
	/**
	 * Generate an url for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  bool  $absolutePath
	 * @return string
	 */
	function theme_asset(string $asset, $absolutePath = true, bool $version = true)
	{
		return \Theme::url($asset, $absolutePath, $version);
	}
}

if (!function_exists('theme_style')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  bool  $absolutePath
	 * @return string
	 */
	function theme_style(string $asset, $absolutePath = true, bool $version = true)
	{
		return \Theme::style($asset, $absolutePath, $version);
	}
}

if (!function_exists('theme_script')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @param  string  $mode
	 * @param  bool  $absolutePath
	 * @param  string  $type
	 * @param  string  $level
	 * @return string
	 */
	function theme_script(string $asset, string $mode = '', $absolutePath = true, string $type = 'text/javascript', string $level = 'functionality', bool $version = true)
	{
		return \Theme::script($asset, $mode, $absolutePath, $type, $level, $version);
	}
}


if (!function_exists('theme_image')) {
	/**
	 * Generate a secure asset path for the theme asset.
	 *
	 * @param  string  $asset
	 * @return string
	 */
	function theme_image(string $asset, string $alt = '', string $class = '', array $attributes = [], $absolutePath = true, bool $version = true)
	{
		return \Theme::image($asset, $alt, $class, $attributes, $absolutePath, $version);
	}
}

if (!function_exists('isValidJson')) {
    function isValidJson(string $str) {
        $str = trim($str);
        if (!empty($str)) {
            @json_decode($str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;        
    }
}

if (!function_exists('primary_menu')) {
    function primary_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::PRIMARY_MENU);
    }
}

if (!function_exists('secondary_menu')) {
    function secondary_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::SECONDARY_MENU);
    }
}

if (!function_exists('top_menu')) {
    function top_menu()
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::TOP_MENU);
    }
}

if (!function_exists('get_menu')) {
    function get_menu(string $type = 'primary_menu')
    {
        return app(MenuRepository::class)->getAllWithChilds(MenuPositionEnum::getValue($type));
    }
}

if(!function_exists('get_theme_image')) {
    function get_theme_image($key, $default = null)
    {
        if(blank(config('appearance.theme.'.$key))) {
            return $default;
        }
        
        return uploadPath(config('appearance.theme.'.$key));
    }
}

if(!function_exists('get_theme_option')) {
    function get_theme_option($key, $default = null)
    {
        return config('appearance.theme.'.$key, $default);
    }
}

if(!function_exists('menu_render')) {
    function menu_render(string $position, $view = 'appearance::default-menu.index')
    {
        $func = $position.'_menu';
        if(!function_exists($func)) {
            return [];
        }

        if(!View::exists($view)) {
            Log::error("menu_render() -> Menu {$view} not found.");
            return [];
        }

        $menu = $func();
        return View::make($view, compact('menu'))->render();
    }
}