<?php

namespace App\Console\Commands;

use App\Models\Experiences\Experience;
use App\Models\House\House;
use Illuminate\Console\Command;

class RefreshCacheCommand extends Command
{
    protected $signature = 'refresh:cache';

    protected $description = 'Refresh cache for houses and experiences';

    public function handle(): void
    {
        House::chunk(10, function ($houses) {
            forEach($houses as $house) {
                \Cache::forget('relevance_house_'.$house->getKey());
                \Cache::forget(House::class.'_'.$house->getKey());
                $house->searchScore([]);
                $house->formatToList();
            }
        });

        Experience::chunk(10, function ($experiences) {
            forEach($experiences as $experience) {
                \Cache::forget('relevance_experience_'.$experience->getKey());
                $experience->searchScore([]);
                \Cache::forget(Experience::class.'_'.$experience->getKey());
                $experience->formatToList();

            }
        });
    }
}
