<?php

namespace App\Services\Wordpress;

use App\Models\House;
use App\Models\Settings\HouseType;
use function PHPUnit\Framework\isArray;
use function PHPUnit\Framework\isString;

class ImportService
{
    public function __construct()
    {
    }

    private function getEndpoint()
    {
        return config('wordpress.domain').'/wp-json/wp/v2/'.config('wordpress.house_post_type');
    }
    public function import()
    {
        $houses = $this->getAllHouses();

        foreach ($houses as $house) {
            #dd($this->getHouseById($house['id']));
            $this->saveHouse($this->getHouseById($house['id']));
        }
    }

    private function getAllHouses()
    {
        return \Http::get($this->getEndpoint().'?_fields=id')->json();
    }

    private function getHouseById($id)
    {
        return \Http::get($this->getEndpoint().'/'.$id)->json();
    }

    private function saveHouse(array $house)
    {
        $houseModel = $this->initializeHouseModel($house);
        $this->syncPrices($houseModel, $house);
        $this->syncBookedDates($houseModel, $house);
        $this->updateHouseDetails($houseModel, $house);
        $this->attachFeaturedMedia($houseModel, $house);
        $this->attachAdditionalMedia($houseModel, $house);
    }

    private function initializeHouseModel(array $house): House
    {
        $houseModel = House::firstOrNew(['wp_id' => $house['id']]);

        $houseModel->setTranslationForAllLanguages('name', $house['title']['rendered']);
        $houseModel->setTranslationForAllLanguages('description', $house['content']['rendered']);
        $houseModel->is_disabled = $house['status'] !== 'publish';
        $houseModel->street_name = $house['property_address'];
        $houseModel->street_number = '';
        $houseModel->zip = $house['property_zip'];
        $houseModel->state = $house['property_state'];
        $houseModel->country = $house['property_country'];
        $houseModel->latitude = $house['property_latitude'];
        $houseModel->longitude = $house['property_longitude'];
        $houseModel->default_price = ((int)$house['property_price']);
        $houseModel->house_type_id = $this->getHouseTypeId($house['property_category'][0]);

        $houseModel->save();
        $houseModel->refresh();

        return $houseModel;
    }

    private function getHouseTypeId(string $category): int
    {
        return HouseType::where('wp_category', $category)->first()->id;
    }

    private function updateHouseDetails(House $houseModel, array $house): void
    {
        $houseModel->details()->updateOrCreate([], [
            'area' => $house['property_size'],
            'num_guest' => $house['guest_no'],
            'num_bedrooms' => $house['property_bedrooms'],
            'num_bathrooms' => $house['property_bathrooms'],
            'check_in_time' => $this->convertToTime($house['check-in-hour']),
            'check_out_time' => $this->convertToTime($house['check-out-hour']),
            'private_bathroom' => $house['private-bathroom'] === 'yes',
            'private_entrance' => $house['private-entrance'] === 'yes',
            'family_friendly' => $house['familyfriendly'] === 'yes',
        ]);
    }

    private function attachFeaturedMedia(House $houseModel, array $house): void
    {
        $featuredMediaUrl = \Http::get($house['_links']['wp:featuredmedia'][0]['href'])->json()['media_details']['sizes']['full']['source_url'];
        $fileName = basename($featuredMediaUrl);

        if (!$houseModel->media()->where('collection_name', 'house_image')->where('file_name', $fileName)->exists()) {
            $featured = $houseModel->addMediaFromUrl($featuredMediaUrl)->toMediaCollection('house_image');
            $featured->order_column = 1;
            $featured->save();
        }
    }

    private function attachAdditionalMedia(House $houseModel, array $house): void
    {
        $mediaItems = \Http::get($house['_links']['wp:attachment'][0]['href'])->json();

        foreach ($mediaItems as $item) {
            $mediaUrl = $item['media_details']['sizes']['full']['source_url'];
            $fileName = basename($mediaUrl);

            if (!$houseModel->media()->where('collection_name', 'house_image')->where('file_name', $fileName)->exists()) {
                $houseModel->addMediaFromUrl($mediaUrl)->toMediaCollection('house_image');
            }
        }
    }

    private function syncPrices(House $houseModel, array $house)
    {
        if ($house['custom_price'] == "") {
            return;
        }
        foreach ($house['custom_price'] as $date => $price) {
            $priceModel = $houseModel->prices()->updateOrCreate([
                'date' => date('Y-m-d', $date),
            ], [
                'price' => $price,
            ]);

            $megaDetails = $house['mega_details'][$date];

            $priceModel->details()->updateOrCreate([], [
                'min_days_booking' => $megaDetails['period_min_days_booking'],
                'extra_price_per_guest' => $megaDetails['period_extra_price_per_guest'],
                'price_per_weekend' => $megaDetails['period_price_per_weekeend'],
                'checkin_change_over' => $megaDetails['period_checkin_change_over'],
                'checkin_checkout_change_over' => $megaDetails['period_checkin_checkout_change_over'],
                'price_per_month' => $megaDetails['period_price_per_month'],
                'price_per_week' => $megaDetails['period_price_per_week'],
            ]);
        }
    }

    private function syncBookedDates(House $houseModel, array $house)
    {
        foreach ($house['booking_dates'] as $date => $reason) {
            $houseModel->disableDates()->create([
                'date' => date('Y-m-d', $date),
                'reason' => $reason,
            ]);
        }
    }

    private function convertToTime($time)
    {
        if (preg_match('/^\d{1,2}h\d{2}$/', $time)) {
            return str_replace('h', ':', $time) . ':00';
        }
        return $time;
    }
}
