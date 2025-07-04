<?php

namespace App\Services;

use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelSettings\Settings;
use Spatie\Translatable\HasTranslations;

class TranslationService
{
    protected $translate;

    public function __construct()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.config('services.google.google_json_key'));
        $this->translate = new TranslationServiceClient();
    }

    private function translateText(string $text, string $targetLanguage, string $sourceLanguage)
    {
        if ($text === "")
            return "";

        $projectId = config('services.google.project_id');
        $location = 'global';

        $formattedParent = $this->translate->locationName($projectId, $location);

        if ($targetLanguage === 'pt-PT'){
            $targetLanguage = 'PT';
        }
        try {
            $request = new TranslateTextRequest();
            $request->setTargetLanguageCode($targetLanguage);
            $request->setSourceLanguageCode($sourceLanguage);
            $request->setMimeType('text/html');
            $request->setContents([$text]);
            $request->setParent($formattedParent);
        }catch (\Exception $e){
            dd($targetLanguage);
        }


        $response = $this->translate->translateText($request);


        return $response->getTranslations()[0]->getTranslatedText();

    }
    /**
     * @var HasTranslations $model
     */
    public function translate(Model $model, string $originLanguage, array $attributes, array $targetLanguage): void
    {
        foreach ($attributes as $attribute){
            foreach ($targetLanguage as $lang){
                $model->setTranslation($attribute, $lang, $this->translateText($model->getTranslation($attribute, $originLanguage), $lang, $originLanguage));
            }
        }
        $model->save();
    }

    public function translateSettings(Settings $settings, string $originLanguage, array $attributes, array $targetLanguage): void
    {
        foreach ($attributes as $attribute){
            foreach ($targetLanguage as $lang){
                $settings->{$attribute}[$lang] =  $this->translateText($settings->{$attribute}[$originLanguage], $lang, $originLanguage);
            }
        }
        $settings->save();
    }
}
