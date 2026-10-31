<?php

namespace App\Videos;

use App\Enums\Quality;
use App\Models\Video;
use App\Videos\VideoSetup;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoData
{
    /**
     * Create a new class instance.
     */
    private $path;
    private $ffprobe;
    private $media;
    private $videoStream;
    private $isAvailable;
   

    public function __construct(public Video $video)
    {
        $this->path = public_path('storage').DIRECTORY_SEPARATOR.ltrim($this->video->video_path, '/\\');
        $this->ffprobe = FFProbe::create();
        $this->isAvailable = file_exists($this->path);
        if (! $this->isAvailable) {
            throw new \Exception(__('Video file Dose not exist in ').$this->path);
        }
        $this->media = FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path);
        $this->videoStream = $this->ffprobe->streams($this->path)->videos()->first();
    }

    public function getVideoDurationInSeconds()
    {
        return $this->media->getDurationInSeconds();
    }

    public function getVideoHeight()
    {
        return $this->videoStream->get('height');
    }

    public function getVideoWidth()
    {
        return $this->videoStream->get('width');
    }

    public function isLongitudinal()
    {
        return $this->getVideoHeight() > $this->getVideoWidth();
    }

    public function getVideoQuality()
    {
        if ($this->isLongitudinal()) {
            foreach (VideoSetup::$dimensions as $key => $value) {
                if ($this->getVideoHeight() >= $value[0] && $this->getVideoWidth() >= $value[1]) {
                    return $key;
                }

            }
            return Quality::Q240->value;
        } else {
            foreach (VideoSetup::$dimensions as $key => $value) {
                if ($this->getVideoHeight() >= $value[1] && $this->getVideoWidth() >= $value[0]) {
                    return $key;
                }

            }
            return Quality::Q240->value;
        }
    }
}
