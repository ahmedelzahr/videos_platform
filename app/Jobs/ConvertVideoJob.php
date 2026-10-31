<?php

namespace App\Jobs;

use App\Models\Video;
use App\Videos\VideoConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ConvertVideoJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(Public Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $videoConverter=new VideoConverter($this->video);
        $videoConverter->convertVideo();
    }
}
