<?php
namespace Modules\Appearance\Shortcodes;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        try {
            return view('partials.'.$shortcode->name, ['data' => $shortcode, 'content' => $content, 'viewData' => $compiler])->render();
        } catch (Throwable $e) {
            Log::error($e);
            return '';
        }
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