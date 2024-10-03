<?php

namespace App\Listeners;

use App\Models\QueueForCompressionVideo;
use CloudConvert\Laravel\Facades\CloudConvert;
use CloudConvert\Models\WebhookEvent;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;
use Illuminate\Support\Facades\Log;

class CloudConvertEventListener
{

    public function onJobFinished(WebhookEvent $event)
    {

        $job = $event->getJob();

        if($tag = $job->getTag()){
            if($queueForCompressionVideo = QueueForCompressionVideo::where('video', $tag)->where('is_complete', 1)->first()){
                $queueForCompressionVideo->is_complete = 2;
                $queueForCompressionVideo->save();

                $exportTask = $job->getTasks()
                                  ->whereStatus(Task::STATUS_FINISHED)
                                  ->whereName('export-my-file')[0];

                /*\App\Models\Log::add([
                    'description' => json_encode($exportTask->getResult())
                ]);*/

                foreach ($exportTask->getResult()->files as $file) {
                    /*\App\Models\Log::add([
                        'description' => json_encode($file)
                    ]);*/

                    if(file_exists(storage_path().'/app/public/file/'. $file->filename)) {
                        $source = CloudConvert::getHttpTransport()->download($file->url)->detach();
                        $dest = fopen(storage_path().'/app/public/file/'. $file->filename, 'w');
                        stream_copy_to_stream($source, $dest);
                    }
                }
            }
        }






    }

    public function onJobFailed(WebhookEvent $event) {

        $job = $event->getJob();

        if($tag = $job->getTag()) {
            if ($queueForCompressionVideo = QueueForCompressionVideo::where('video', $tag)->where('is_complete', 1)->first()) {
                $queueForCompressionVideo->is_complete = -1;
                $queueForCompressionVideo->save();

                $failingTask =  $job->getTasks()->whereStatus(Task::STATUS_ERROR)[0];
            }
        }

        //Log::error('CloudConvert task failed: ' . $failingTask->getId());

    }

    public function subscribe($events)
    {
        $events->listen(
            'cloudconvert-webhooks::job.finished',
            'App\Listeners\CloudConvertEventListener@onJobFinished'
        );

        $events->listen(
            'cloudconvert-webhooks::job.failed',
            'App\Listeners\CloudConvertEventListener@onJobFailed'
        );
    }

}