<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * php artisan db:seed
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    static function defaultRun(Faker $faker)
    {
    }
}
