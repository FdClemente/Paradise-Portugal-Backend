<?php

namespace App\Jobs;

use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class TranslateSettingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Settings $settings, public string $originLanguage, public ?array $attribute = null,public ?array $target = null)
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $model = $this->settings;

        $target = [];
        if ($this->target){
            $target = $this->target;
        }

        $attribute = [];
        if ($this->attribute){
            $attribute = $this->attribute;
        }

        $translationService = app(TranslationService::class);

        $translationService->translateSettings($model, $this->originLanguage, $attribute, $target);

    }
}
