<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class MapsController extends Controller
{

    private function make_request($longitude, $latitude) {
        $base_url = config('map.base_url');
        $query_parameters = [
            'style' => 'osm-carto',
            'width' => 600,
            'height' => 450,
            'center' => 'lonlat:'.$longitude.','.$latitude,
            'zoom' => 20,
            'apiKey' => config('map.api_key'),
        ];
        return Http::get($base_url . $this->query_parameters_generator($query_parameters));
    }

    private function query_parameters_generator(array $query_parameters): string
    {
        $string_query = '?';
        foreach ($query_parameters as $key => $value) {
            $string_query .= $key . '=' . $value . '&';
        }

        return $string_query;
    }

    public function store_one() {
//        return Image::make($this->make_request()->body())->response();
    }

    public function main() {
        $first_longitude = $longitude = 59.563134;
        $first_latitude = $latitude = 36.360594;
        for($x=0; $x<5; $x++) {
            for($y=0; $y<5; $y++) {
//                Storage::put($x . '-' . $y .'.jpeg', $this->make_request($longitude, $latitude)->body());
                $croppedImage = Image::make($this->make_request($longitude, $latitude)->body());
                $croppedImage->crop(600, 400);
                $croppedImage->save(storage_path($x . '-' . $y .'.jpeg'));
                $longitude += 0.000402;
            }
            $longitude = $first_longitude;
            $latitude -= 0.000217;
        }
    }
}
