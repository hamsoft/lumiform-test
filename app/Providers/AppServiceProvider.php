<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            Question::MODEL_TYPE => Question::class,
            Section::MODEL_TYPE => Section::class,
            Page::MODEL_TYPE => Page::class,
        ]);
    }
}
