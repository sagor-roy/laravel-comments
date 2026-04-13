<?php

namespace SagorRoy\Comments;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CommentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/comments.php',
            'comments'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/database/migrations' => database_path('migrations'),
            ], 'comments-migrations');

            $this->publishes([
                __DIR__.'/config/comments.php' => config_path('comments.php'),
            ], 'comments-config');

            $this->publishes([
                __DIR__.'/resources/views' => resource_path('views/vendor/comments'),
            ], 'comments-views');

            $this->publishes([
                dirname(__DIR__, 1).'/public/css/comments.css' => public_path('vendor/comments/comments.css'),
                __DIR__.'/resources/views/comments.js' => public_path('vendor/comments/comments.js'),
            ], 'comments-assets');
        }

        $this->loadViewsFrom(
            __DIR__.'/resources/views',
            'comments'
        );

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/lang', 'comments');

        Blade::directive('comments', function ($expression) {
            return "<?php echo view('comments::comments', ['model' => {$expression}])->render(); ?>";
        });
    }
}
