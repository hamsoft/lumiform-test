<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Question;
use App\Models\Section;
use App\Services\Analytics\AnalyticsService;
use App\Services\Analytics\AnalyticsServiceImpl;
use App\Services\AnswerService;
use App\Services\AnswerValidator;
use App\Services\Form\FormItemService;
use App\Services\FormService;
use App\Services\Implementations;
use App\Services\PageService;
use App\Services\QuestionService;
use App\Services\SectionService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        FormService::class => Implementations\FormServiceImpl::class,
        FormItemService::class => Implementations\FormItemServiceImpl::class,
        QuestionService::class => Implementations\QuestionServiceImpl::class,
        PageService::class => Implementations\PageServiceImpl::class,
        SectionService::class => Implementations\SectionServiceImpl::class,
        AnswerService::class => Implementations\AnswerServiceImpl::class,
        AnswerValidator::class => Implementations\AnswerValidatorImpl::class,
        AnalyticsService::class => AnalyticsServiceImpl::class,
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
