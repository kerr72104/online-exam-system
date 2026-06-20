<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Exam;
use App\Models\Question;
use App\Policies\ExamPolicy;
use App\Policies\QuestionPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Exam::class, ExamPolicy::class);
        Gate::policy(Question::class, QuestionPolicy::class);
    }
}
