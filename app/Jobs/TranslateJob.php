<?php

namespace App\Jobs;

use App\Services\TranslationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Translatable\HasTranslations;

class TranslateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Model $model, public string $originLanguage, public ?array $attribute = null,public ?array $target = null)
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        if(!in_array(HasTranslations::class,class_uses_recursive($this->model))){
            throw new \Exception('Model does not use HasTranslations trait');
        }

        /**
         * @var HasTranslations $model
         */
        $model = $this->model;
        if (!property_exists($model, 'translatable')){
            throw new \Exception('Model does not have translatable property');
        }
        $target = [];
        if ($this->target){
            $target = $this->target;
        }

        $attribute = [];
        if ($this->attribute){
            $attribute = $this->attribute;
        }

        $translationService = app(TranslationService::class);

        $translationService->translate($model, $this->originLanguage, $attribute, $target);

    }
}
