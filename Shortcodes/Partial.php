<?php
namespace Modules\Appearance\Shortcodes;

use Illuminate\Contracts\Container\BindingResolutionException;

class Partial
{
    /**
     * Register shortcode
     * 
     * @param mixed $shortcode 
     * @param mixed $content 
     * @param mixed $compiler 
     * @param mixed $name 
     * @param mixed $viewData 
     * @return void 
     */
    public function register($shortcode, $content, $compiler, $name, $viewData)
    {
        return view('partials.'.$shortcode->name)->render();
    }

    /**
     * Get info of shortcode
     * @return string|array|null 
     * @throws BindingResolutionException 
     */
    public static function info(): array
    {
        return [
            'tag'  => 'partial',
            'name' =>  __('appearance::messages.shortcodes.partial'),
            'attributes' => [
                'name' => 'partial_name'
            ]
        ];
    }
}