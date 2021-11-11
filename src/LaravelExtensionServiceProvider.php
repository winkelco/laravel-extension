<?php

namespace WinkelCo\LaravelExtension;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use WinkelCo\LaravelExtension\Facades\Hook;
use WinkelCo\LaravelExtension\Facades\Extension;
use WinkelCo\LaravelExtension\Commands\ExtensionInitCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionListCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionEnableCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionDisableCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionInspectCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionNewCommand;
use WinkelCo\LaravelExtension\Commands\ExtensionUpdateListCommand;
use WinkelCo\LaravelExtension\Commands\HookListCommand;

class LaravelExtensionServiceProvider extends ServiceProvider
{
    private $loaded = [];
    private $registered = [];
    private $booted = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
        //define('HOOK_SYSTEM_PRIORITY', 15);

        // config
        $this->publishConfig();
        $this->bindConfig();

        // bind
        $this->bindClass();

        // register command
        $this->makeCustomConsoleCommand();

        // register blade directive
        $this->makeCustomBladeDirective();

        // 
        $this->loadViewsFrom( __DIR__ . "/Views", "LaravelExtension");

        // load and register a extension
        $this->loaded = Extension::load();
        $this->registered = Extension::register($this->loaded);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // boot a extensionsion
        $this->booted = Extension::boot($this->registered);

        // save
        $this->app->bind('extension.loaded', function () { return $this->loaded; });
        $this->app->bind('extension.registered', function () { return $this->registered; });
        $this->app->bind('extension.booted', function () { return $this->booted; });
    }


    /**
     * Bind Config to Application Container
     *
     * @return void
     */
    private function bindConfig()
    {
        $configPath = __DIR__ . '/../config/extension.php';
        $this->mergeConfigFrom($configPath, 'extension');

        $this->app->bind('extension.config', function () {
            return $this->app['config']->get('extension');
        });
    }

    /**
     * Binc Class to Application Container
     *
     * @return void
     */
    private function bindClass()
    {
        $this->app->bind('extension', function () {
            $config = $this->app['config']->get('extension');
            return new \WinkelCo\LaravelExtension\Extension($config['path'], $config['debug']);
        });
        $this->app->bind('hook', function () {
            return new \WinkelCo\LaravelExtension\Hook;
        });
        $this->app->bind('menu', function () {
            return new \WinkelCo\LaravelExtension\Menu;
        });
    }

    /**
     * For Publish Config Laravel
     *
     * @return void
     */
    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/' => base_path('config'),
        ]);
    }


    /**
     * Register command
     *
     * @return void
     */
    private function makeCustomConsoleCommand()
    {
        $this->commands([
            ExtensionInitCommand::class,
            ExtensionListCommand::class,
            ExtensionInspectCommand::class,
            ExtensionEnableCommand::class,
            ExtensionDisableCommand::class,
            ExtensionUpdateListCommand::class,
            ExtensionNewCommand::class,
            HookListCommand::class,
        ]);
    }

    /**
     * Register blade directive
     *
     * @return void
     */
    private function makeCustomBladeDirective()
    {
        Blade::directive('applyfilter', function ($expression) {
            $args = explode(', ', $expression);
            $result = '';
            if (count($args) == 1)
            {
                $name = $args[0]; array_splice($args, 0, 1);
                $args_str = (count($args) > 0) ? ", " . implode(', ', $args) : "";
                $result = "<?php \$name = {$name}; \$args_str = '$args_str'; ob_start(); ?>";
            } else {
                $name = $args[0];
                $value = $args[1]; array_splice($args, 0, 2);
                $args_str = (count($args) > 0) ? ", " . implode(', ', $args) : "";
                $result = "<?php echo \WinkelCo\LaravelExtension\Facades\Hook::applyFilter({$name}, {$value}{$args_str}); ?>";
            }
            return $result;
        });

        Blade::directive('endapplyfilter', function ($expression) {
            $result = "<?php \$value = ob_get_contents(); ob_end_clean(); ?>";
            $result .= "<?php echo \WinkelCo\LaravelExtension\Facades\Hook::applyFilter(\$name, \$value); ?>";
            return $result;
        });
    }
}
