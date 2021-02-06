<?php

declare(strict_types=1);

namespace Rinvex\Forms\Providers;

use Rinvex\Forms\Models\Form;
use Rinvex\Forms\Models\FormResponse;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Forms\Console\Commands\MigrateCommand;
use Rinvex\Forms\Console\Commands\PublishCommand;
use Rinvex\Forms\Console\Commands\RollbackCommand;

class FormsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

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
        $this->registerModels([
            'rinvex.forms.form' => Form::class,
            'rinvex.forms.form_response' => FormResponse::class,
        ]);

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->publishesConfig('rinvex/laravel-forms');
        $this->publishesMigrations('rinvex/laravel-forms');
        ! $this->autoloadMigrations('rinvex/laravel-forms') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
