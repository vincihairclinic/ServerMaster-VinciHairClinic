<?php

namespace App\Http\Controllers\Web;

use App\AppConf;
use App\Models\Book;
use App\Models\BookPostAdditional;
use App\Models\BookPostAdditionalItem;
use App\Models\File;
use App\Models\User;
use App\Repositories\Base\TranslateRepository;
use App\Repositories\Base\YoutubeRepository;
use Faker\Factory;
use Illuminate\Support\Facades\Storage;

class TestController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 240);
        ini_set('memory_limit', '10096M');
    }

    public function index()
    {
        ini_set('max_execution_time', 240);
        ini_set('memory_limit', '10096M');
        if(!config('app.debug')) {
            abort(404);
        }

        foreach (Book::all() as $book) {
        $book->post_content_en = str_replace('qwe_asd_zxc_1', '<b>', $book->post_content_en);
        $book->post_content_en = str_replace('qwe_asd_zxc_2',  '</b>', $book->post_content_en);
        $book->post_content_pt = str_replace('qwe_asd_zxc_3', '<b>', $book->post_content_pt);
        $book->post_content_pt = str_replace('qwe_asd_zxc_4',  '</b>', $book->post_content_pt);
//        $book->post_content_pt = str_replace(' >', '>', $book->post_content_pt);
//        $book->post_content_pt = str_replace('var(--dashboard-font-family);', 'Montserrat, HelveticaNeueCyr, sans-serif;', $book->post_content_pt);
        $book->save();

    }

        dd(123123);



//        $faker = Factory::create();
//        $faker->title();
//        $additionals = new BookPostAdditional();
//        $additionals->title = $faker->realText(50);
//        $additionals->content = $faker->text(900);
//        $additionals->language_key = ['en', 'pt'][rand(0,1)];
//        $additionals->book_id = rand(1,2);
//        $additionals->save();
//        for ($i = 0; $i <= rand(4, 12); $i++) {
//            $additionalsItem = new BookPostAdditionalItem();
//            $additionalsItem->title = $faker->realText(50);
//            $additionalsItem->content = $faker->text(150);
//            $additionalsItem->book_post_additional_id = $additionals->id;
//            $additionalsItem->save();
//        }
//
//        $faker = Factory::create();
//        $faker->title();
//        $additionals = new BookPostAdditional();
//        $additionals->content = $faker->text(900);
//        $additionals->language_key = ['en', 'pt'][rand(0,1)];
//        $additionals->book_id = rand(1,2);
//        $additionals->save();
//        for ($i = 0; $i <= rand(4, 12); $i++) {
//            $additionalsItem = new BookPostAdditionalItem();
//            $additionalsItem->content = $faker->text(150);
//            $additionalsItem->book_post_additional_id = $additionals->id;
//            $additionalsItem->save();
//        }
//
//        $faker = Factory::create();
//        $faker->title();
//        $additionals = new BookPostAdditional();
//        $additionals->title = $faker->realText(50);
//        $additionals->language_key = ['en', 'pt'][rand(0,1)];
//        $additionals->book_id = rand(1,2);
//        $additionals->save();
//        for ($i = 0; $i <= rand(4, 12); $i++) {
//            $additionalsItem = new BookPostAdditionalItem();
//            $additionalsItem->title = $faker->realText(50);
//            $additionalsItem->book_post_additional_id = $additionals->id;
//            $additionalsItem->save();
//        }
//        $files = File::select('name')->get();
////        $v = basename('/var/www/dashboard/public/HOLLY_WARM_UP.mov.mp4');
//        foreach ($files as $file) {
//            $v = File::where('name', $file->name)->first();
//            $v->size = strlen($v->content);
//            $v->save();
//        }

//dd(\Hash::check(hash('sha256', 'sad254tfdf2'), User::where('email', 'admin@gmail.com')->first()->password));

//        User::where('email', 'admin@gmail.com')->first()->update([
//            'password' => \Hash::make(hash('sha256', 'sad254tfdf2')),
//        ]);

        //dd(hash('sha256', 'sad254tfdf2'), User::where('email', 'admin@gmail.com')->first()->password);
//dd(\Hash::check(hash('sha256', 'sad254tfdf2'), User::where('email', 'admin@gmail.com')->first()->password));

        /*User::where('email', 'admin@gmail.com')->first()->update([
            'password' => \Hash::make(hash('sha256', 'sad254tfdf2')),
        ]);*/

        //dd(TranslateRepository::translate('The internet is a big network', 'uk', 'en'));

        return 1;
    }



}





