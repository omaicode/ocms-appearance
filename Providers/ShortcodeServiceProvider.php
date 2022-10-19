<?php
namespace Modules\Appearance\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Appearance\Shortcodes\Partial;
use Shortcode;

class ShortcodeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Shortcode::register('partial', Partial::class);        
    }
}