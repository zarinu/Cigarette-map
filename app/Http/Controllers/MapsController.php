<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class MapsController extends Controller
{

    private function make_request() {
        $base_url = config('map.base_url');
        $query_parameters = [
            'style' => 'osm-carto',
            'width' => 600,
            'height' => 400,
            'center' => 'lonlat:59.563134,36.360594',
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
        Storage::put('example.jpeg', $this->make_request()->body());
//        return Image::make($this->make_request()->body())->response();
    }
}
