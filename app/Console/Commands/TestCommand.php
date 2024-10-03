<?php

namespace App\Console\Commands;

use App\AppConf;
use App\Application;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class TestCommand extends Command
{
    protected $signature = 'test-run {--fullUrl=}';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        AppConf::load($this->option('fullUrl'));
        Application::$isConsole = true;

        echo "--- start\n";

        self::proc();


        echo "-------------- end\n";
        return;
    }

    //--------------------------------------------------

    static function proc()
    {
        $imagesDirectory = '/var/www/api/storage/app/public/image/';
        if($dir = opendir($imagesDirectory)){
            while(($file = readdir($dir))!== false){
                $imagePath = $imagesDirectory.$file;
                if($file != '.' && $file != '..' && file_exists($imagePath)){
                    $image = Image::make($imagePath)->orientate();
                    if($image->width() > $image->height() && $image->width() > 1024){
                        $image = $image->widen(1024)->encode(null, 80);
                    }else if($image->width() < $image->height() && $image->height() > 1024){
                        $image = $image->heighten(1024)->encode(null, 80);
                    }else{
                        $image = $image->encode(null, 80);
                    }

                    Storage::disk('image')->put($file, (string)$image);


                    echo $file."\n";
                }
            }
            closedir($dir);
        }

        return 1;
    }

}
