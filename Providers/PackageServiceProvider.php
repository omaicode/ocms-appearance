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
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;

class PackageServiceProvider extends ServiceProvider
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
        // $this->registerConfigs();

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
        return [ThemesManager::class];
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
}
