<?php

namespace App\Services\Wordpress;

use App\Models\House;
use App\Models\Settings\HouseType;

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
        $houseModel = House::firstOrNew(['wp_id' => $house['id']]);
        /*$houseModel->setTranslationForAllLanguages('name', $house['title']['rendered']);
        $houseModel->setTranslationForAllLanguages('description', $house['content']['rendered']);
        $houseModel->is_disabled = $house['status'] !== 'publish';
        $houseModel->street_name = $house['property_address'];
        $houseModel->street_number = '';
        $houseModel->zip = $house['property_zip'];
        $houseModel->state = $house['property_state'];
        $houseModel->country = $house['property_country'];
        $houseModel->latitude = $house['property_latitude'];
        $houseModel->longitude = $house['property_longitude'];
        $houseModel->default_price = ((int)$house['property_price']) * 100;
        $houseModel->house_type_id = HouseType::where('wp_category', $house['property_category'][0])->first()->id;
        $houseModel->save();
        $houseModel->refresh();

        $houseModel->details()->updateOrCreate([
            'area' => $house['property_size'],
            'num_guest' => $house['guest_no'],
            'num_bedrooms' => $house['property_bedrooms'],
            'num_bathrooms' => $house['property_bathrooms'],
            'check_in_time' => $this->convertToTime($house['check-in-hour']),
            'check_out_time' => $this->convertToTime($house['check-out-hour']),
            'private_bathroom' => $house['private-bathroom'] === 'yes',
            'private_entrance' => $house['private-entrance'] === 'yes',
            'family_friendly' => $house['familyfriendly'] === 'yes',
        ]);*/

        $featuredMedia = \Http::get($house['_links']['wp:featuredmedia'][0]['href'])->json();
        if (!$houseModel->media()->where('collection_name', 'house_image')->where('file_name', basename($featuredMedia['media_details']['sizes']['full']['source_url']))->exists()){
            $featured = $houseModel->addMediaFromUrl($featuredMedia['media_details']['sizes']['full']['source_url'])
                ->toMediaCollection('house_image');

            $featured->order_column = 1;
            $featured->save();
        }

        $media = \Http::get($house['_links']['wp:attachment'][0]['href'])->json();

        foreach ($media as $item) {
            if (!$houseModel->media()->where('collection_name', 'house_image')->where('file_name', basename($item['media_details']['sizes']['full']['source_url']))->exists()){
                $houseModel->addMediaFromUrl($item['media_details']['sizes']['full']['source_url'])
                    ->toMediaCollection('house_image');
            }
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
