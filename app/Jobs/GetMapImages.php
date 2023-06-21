<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class GetMapImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $max_x = 5;
        $max_y = 5;
        $longitude = 59.563134;
        $latitude = 36.360594;
        if (!empty($data['longitude']) && !empty($data['latitude'])) {
            $longitude = $data['longitude'];
            $latitude = $data['latitude'];
        }
        if (!empty($data['max_x'])) {
            $max_x = $data['max_x'];
        }
        if (!empty($data['max_y'])) {
            $max_y = $data['max_y'];
        }
        $first_longitude = $longitude;
        for ($x = 0; $x < $max_x; $x++) {
            for ($y = 0; $y < $max_y; $y++) {
                $croppedImage = Image::make($this->make_request($longitude, $latitude)->body());
                $croppedImage->crop(600, 400);
                $croppedImage->save(storage_path($x . '-' . $y . '.jpeg'));
                $longitude += 0.000402;
            }
            $longitude = $first_longitude;
            $latitude -= 0.000217;
        }
    }

    private function make_request($longitude, $latitude)
    {
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
}
