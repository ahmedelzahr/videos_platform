<?php

namespace App\Videos;

use App\Enums\Quality;

class VideoSetup
{
    /**
     * Create a new class instance.
     */

     public static $kiloBitrates=[
        
        Quality::Q240->value=>300,
        Quality::Q360->value=>500,
        Quality::Q480->value=>750,
        Quality::Q720->value=>2048,
        Quality::Q1060->value=>4096,  
    ];

     public static $dimensions= [
        Quality::Q1060->value => [1920, 1080],
        Quality::Q720->value=> [1280, 720],
        Quality::Q480->value=> [854, 480],
        Quality::Q360->value=> [640, 360],
        Quality::Q240->value=> [426, 240],
    ];
    public function __construct()
    {
        //
    }


}
