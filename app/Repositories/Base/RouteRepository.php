<?php

namespace App\Repositories\Base;

class RouteRepository
{

    /*
     * RouteHelper::grudDashboard('user');
     */
    static function crudDashboard($name)
    {
        $nameLower = mb_strtolower($name);
        $nameController = '';

        if(strpos($nameLower, '-')){
            foreach (explode('-', $nameLower) as $item){
                $nameController .= ucfirst($item);
            }
        }else{
            $nameController = ucfirst($nameLower);
        }

        \Route::group(['prefix' => $nameLower], function () use ($nameLower, $nameController){
            \Route::get('', $nameController.'Controller@index')
                ->name('dashboard.'.$nameLower.'.index');
            \Route::post('index-json', $nameController.'Controller@indexJson')
                ->name('dashboard.'.$nameLower.'.index-json');

            \Route::get('create', $nameController.'Controller@create')
                ->name('dashboard.'.$nameLower.'.create');
            \Route::post('store', $nameController.'Controller@store')
                ->name('dashboard.'.$nameLower.'.store');

            \Route::get('{model}/edit', $nameController.'Controller@edit')
                ->where('model', '[0-9]+')
                ->name('dashboard.'.$nameLower.'.edit');
            \Route::post('{model}/update', $nameController.'Controller@update')
                ->where('model', '[0-9]+')
                ->name('dashboard.'.$nameLower.'.update');

            \Route::post('{model}/destroy', $nameController.'Controller@destroy')
                ->where('model', '[0-9]+')
                ->name('dashboard.'.$nameLower.'.destroy');
        });
    }

    static function crudApi($name)
    {
        $nameLower = mb_strtolower($name);
        $nameController = '';

        if(strpos($nameLower, '-')){
            foreach (explode('-', $nameLower) as $item){
                $nameController .= ucfirst($item);
            }
        }else{
            $nameController = ucfirst($nameLower);
        }

        \Route::group(['prefix' => $nameLower], function () use ($nameLower, $nameController){
            \Route::get('', $nameController.'Controller@index')
                ->name('dashboard.'.$nameLower.'.index');
        });
    }
}
