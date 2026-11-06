<?php

namespace App\Videos;

use App\Enums\Format;
use App\Models\Convertedvideo;
use App\Models\Video;
use App\Videos\VideoSetup;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoConverter
{
    /**
     * Create a new class instance.
     */
    public function __construct(public Video $video)
    {
        //
    }

    public function getFileName($fileName, $type)
    {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName).$type;
    }


    public function getFilter($format, $kiloBitrate)
    {
        return $format == Format::MP4->value ? (new X264('aac', 'libx264'))->setKiloBitrate($kiloBitrate) : (new WebM('libvorbis', 'libvpx'))->setKiloBitrate($kiloBitrate);
    }

    private function convertOneVideo($filter, $convertedName, $width, $height)
    {
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)
            ->export()
            ->toDisk(config('filesystems.default'))
            ->inFormat($filter)
            ->addFilter(function (VideoFilters $filters) use ($width, $height) {
                $filters->resize(new Dimension($width, $height));
            })
            ->save($convertedName);

    }

    private function saveConvertedVideo($video_id,$format,$disk,$video_path,$quality){
        $convertedVideo=new Convertedvideo();
        $convertedVideo->video_id=$video_id;
        $convertedVideo->format=$format;
        $convertedVideo->disk=$disk;
        $convertedVideo->video_path=$video_path;
        $convertedVideo->quality=$quality;
        $convertedVideo->save();

    }

    public function convertVideo()
    {
 
        $videoData = new VideoData($this->video);
        foreach (VideoSetup::$kiloBitrates as $key => $kiloBitrate) {
            $convertedMP4Name = $key.'-'.$this->getFileName($this->video->video_path, '.mp4');
            $mp4Filter = $this->getFilter(Format::MP4->value, $kiloBitrate);
            $convertedWebmName = $key.'-'.$this->getFileName($this->video->video_path, '.webm');
            $webmFilter = $this->getFilter(Format::WEBM->value, $kiloBitrate);
            if ($videoData->isLongitudinal()) {
                $this->convertOneVideo($mp4Filter, $convertedMP4Name, VideoSetup::$dimensions[$key][1], VideoSetup::$dimensions[$key][0]);
                $this->convertOneVideo($webmFilter, $convertedWebmName, VideoSetup::$dimensions[$key][1], VideoSetup::$dimensions[$key][0]);
            } else {
                $this->convertOneVideo($mp4Filter, $convertedMP4Name, VideoSetup::$dimensions[$key][0], VideoSetup::$dimensions[$key][1]);
                $this->convertOneVideo($webmFilter, $convertedWebmName, VideoSetup::$dimensions[$key][0], VideoSetup::$dimensions[$key][1]);
            }
            $this->saveConvertedVideo($this->video->id,Format::MP4->value,config('filesystems.default'),$convertedMP4Name,(string)$key);
            $this->saveConvertedVideo($this->video->id,Format::WEBM->value,config('filesystems.default'),$convertedWebmName,(string)$key);
            if ($videoData->getVideoQuality() == $key) {
                break;
        }
    }
    Storage::disk($this->video->disk)->delete($this->video->video_path);
    $hours=floor($videoData->getVideoDurationInSeconds()/3600);
    $minutes=floor($videoData->getVideoDurationInSeconds()/60)%60;
    $seconds=$videoData->getVideoDurationInSeconds()%60;
    $this->video->hours=$hours;
    $this->video->minutes=$minutes;
    $this->video->seconds=$seconds;
    $this->video->longitudinal=$videoData->isLongitudinal();
    $this->video->processed=true;
    $this->video->quality=$videoData->getVideoQuality();
    $this->video->save();
}
}
