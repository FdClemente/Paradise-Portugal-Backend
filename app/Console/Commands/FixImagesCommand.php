<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FixImagesCommand extends Command
{
    protected $signature = 'fix:images';

    protected $description = 'fix format images';

    public function handle(): void
    {
        $mediaItems = Media::all();

        foreach ($mediaItems as $media) {
            dump($media);
            $conversions = $media->generated_conversions;
            $missingConversions = [];

            foreach ($media->getGeneratedConversions() as $conversionName => $isGenerated) {
                if (!$isGenerated) {
                    $missingConversions[] = $conversionName;
                }
            }

            if (!empty($missingConversions)) {
                $media->markAsUnconverted();
                $media->save();
                $media->generateResponsiveImages();
            }
        }
    }
}
