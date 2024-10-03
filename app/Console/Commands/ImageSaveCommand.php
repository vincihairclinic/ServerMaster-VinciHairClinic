<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImageSaveCommand extends Command
{
    protected $signature = 'update-image-save';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
//        AppConf::load($this->option('fullUrl'));
//        Application::$isConsole = true;

        echo "--- start\n";

//        self::proc();


        echo "-------------- end\n";
        return;
    }

    //--------------------------------------------------

    static function proc()
    {
        $file_names = File::select('name')->pluck('name');
        echo "count: ".count($file_names),"\n";
        $count = 1;

        foreach ($file_names as $file_name) {
            $count++;
            $file = File::where('name', $file_name)->first();
            if (empty($file)) {
                continue;
            }
            if (str_contains($file->mime_type, 'image')) {
                echo 'Image  '.$count.'   '.$file_name."\n";
                if (!file_exists(config('filesystems.disks.public.root').'/image/'.$file_name)) {
                    Storage::disk('image')->put($file->name, $file->content);
                } else {
                    echo "          SKIP     Image\n";
                }
            } else {
                echo 'File   '.$count.'   '.$file_name."\n";
                if (!file_exists(config('filesystems.disks.public.root').'/image/'.$file_name)) {
                    Storage::disk('file')->put($file->name, $file->content);
                } else {
                    echo "          SKIP     File\n";
                }
            }
        }
        return 1;
    }

}
