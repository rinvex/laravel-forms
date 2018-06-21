<?php

declare(strict_types=1);

namespace Rinvex\Forms\Providers;

use Rinvex\Forms\Models\Form;
use Rinvex\Forms\Models\FormResponse;
use Illuminate\Support\ServiceProvider;
use Rinvex\Forms\Console\Commands\MigrateCommand;
use Rinvex\Forms\Console\Commands\PublishCommand;
use Rinvex\Forms\Console\Commands\RollbackCommand;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.rinvex.forms.migrate',
        PublishCommand::class => 'command.rinvex.forms.publish',
        RollbackCommand::class => 'command.rinvex.forms.rollback',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.forms');

        // Bind eloquent models to IoC container
        $this->app->singleton('rinvex.forms.form', $formModel = $this->app['config']['rinvex.forms.models.form']);
        $formModel === Form::class || $this->app->alias('rinvex.forms.form', Form::class);

        $this->app->singleton('rinvex.forms.form_response', $formModel = $this->app['config']['rinvex.forms.models.form_response']);
        $formModel === FormResponse::class || $this->app->alias('rinvex.forms.form_response', FormResponse::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Load migrations
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.forms.php')], 'rinvex-forms-config');
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'rinvex-forms-migrations');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}
