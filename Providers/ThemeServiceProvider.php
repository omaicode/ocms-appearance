<?php

namespace Modules\Appearance\Providers;

use Modules\Appearance\Components\Image;
use Modules\Appearance\Components\PageTitle;
use Modules\Appearance\Components\Script;
use Modules\Appearance\Components\Style;
use Modules\Appearance\Console\Commands;
use Modules\Appearance\Console\Generators;
use Modules\Appearance\Facades\ThemesManager as ThemesManagerFacade;
use Modules\Appearance\Http\Middleware;
use Modules\Appearance\Supports\ThemesManager;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Appearance\Compilers\ShortcodeCompiler;
use Modules\Appearance\Facades\Shortcode;
use Modules\Appearance\Supports\Shortcode as SupportsShortcode;
use Modules\Appearance\View\Factory as ShortcodeViewFactory;
use ReflectionClass;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Our root directory for this package to make traversal easier.
     */
    public const PACKAGE_DIR = __DIR__ . '/../../';

    /**
     * Name for this package to publish assets.
     */
    public const PACKAGE_NAME = 'themes-manager';

    /**
     * Pblishers list.
     */
    protected $publishers = [];

    /**
     * Bootstrap the application events.
     */
    public function boot(Router $router)
    {
        $this->loadViewsFrom($this->getPath('resources/views'), 'themes-manager');
        $this->loadViewComponentsAs('theme', [
            Image::class,
            PageTitle::class,
            Script::class,
            Style::class,
        ]);

        $this->strapCommands();

        $router->aliasMiddleware('theme', Middleware\ThemeLoader::class);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->registerShortcode();

        $this->app->singleton('themes-manager', function () {
            return new ThemesManager(
                app(Factory::class),
                app(Translator::class)
            );
        });

        AliasLoader::getInstance()->alias('ThemesManager', ThemesManagerFacade::class);
        AliasLoader::getInstance()->alias('Theme', ThemesManagerFacade::class);

        $this->app->register(BladeServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'shortcode',
            'shortcode.compiler',
            'view',            
            ThemesManager::class
        ];
    }

    /**
     * Get Package absolute path.
     *
     * @param string $path
     */
    protected function getPath($path = '')
    {
        // We get the child class
        $rc = new ReflectionClass(get_class($this));

        return dirname($rc->getFileName()) . '/../' . $path;
    }

    /**
     * Get Module normalized namespace.
     *
     * @param mixed $prefix
     */
    protected function getNormalizedNamespace($prefix = '')
    {
        return Str::start(Str::lower(self::PACKAGE_NAME), $prefix);
    }

    /**
     * Bootstrap our Configs.
     */
    protected function registerConfigs()
    {
        $configPath = $this->getPath('Config');
        $this->mergeConfigFrom(
            "{$configPath}/config.php",
            $this->getNormalizedNamespace()
        );
    }

    protected function strapCommands()
    {
        if ($this->app->runningInConsole() || 'testing' == config('app.env')) {
            $this->commands([
                Commands\ActivateTheme::class,
                Commands\Cache::class,
                Commands\ClearCache::class,
                Commands\DeactivateTheme::class,
                Commands\ListThemes::class,
                Generators\MakeTheme::class,
            ]);
        }
    }  

    protected function registerShortcode()
    {
        $this->app->singleton('shortcode.compiler', function ($app) {
            return new ShortcodeCompiler();
        });

        $this->app->singleton('shortcode', function ($app) {
            return new SupportsShortcode($app['shortcode.compiler']);
        });        

        $finder = $this->app['view']->getFinder();
        $this->app->singleton('view', function ($app) use ($finder) {
            $resolver = $app['view.engine.resolver'];
            $env = new ShortcodeViewFactory($resolver, $finder, $app['events'], $app['shortcode.compiler']);
            $env->setContainer($app);
            $env->share('app', $app);

            return $env;
        });        

        // Register custom shortcodes
        File::ensureDirectoryExists(addon_path());
        $files = File::allFiles(addon_path());
        foreach($files as $file) {
            $class = "\\Addons\\Shortcodes\\{$file}";
            if(property_exists($class, 'tag') && method_exists($class, 'register')) {
                Shortcode::register($class::$tag, $class);
            }
        }
    }
}
