<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use App\Services\AnswerService;
use App\Services\Form\FormItemService;
use App\Services\FormService;
use App\Services\Implementations as ServiceImplementations;
use App\Services\PageService;
use App\Services\QuestionService;
use App\Services\SectionService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        FormService::class => ServiceImplementations\FormServiceImpl::class,
        FormItemService::class => ServiceImplementations\FormItemServiceImpl::class,
        QuestionService::class => ServiceImplementations\QuestionServiceImpl::class,
        PageService::class => ServiceImplementations\PageServiceImpl::class,
        SectionService::class => ServiceImplementations\SectionServiceImpl::class,
        AnswerService::class => ServiceImplementations\AnswerServiceImpl::class,
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
