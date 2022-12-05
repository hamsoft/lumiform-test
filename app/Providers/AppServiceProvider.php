<?php

namespace App\Providers;

use App\Models\Form\FormItem;
use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use App\Services\Form\FormItemElementService;
use App\Services\Form\FormItemService;
use App\Services\FormService;
use App\Services\Implementations as ServiceImplementations;
use App\Services\Implementations\PageService;
use App\Services\Implementations\QuestionService;
use App\Services\Implementations\SectionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        FormService::class => ServiceImplementations\FormService::class,
        FormItemService::class => ServiceImplementations\FormItemService::class,
        QuestionService::class => ServiceImplementations\QuestionService::class,
        PageService::class => ServiceImplementations\PageService::class,
        SectionService::class => ServiceImplementations\SectionService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
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
