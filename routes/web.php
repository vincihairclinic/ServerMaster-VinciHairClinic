<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web', 'middleware' => ['check.env', 'check.get.params']], function () {
    Route::get('test', 'TestController@index');

    try {
        Route::get('{model}', 'PageController@page')
            ->where('model', implode('|', \App\Models\Page::pluck('route')->toArray()))
            ->name('web.page');
    } catch (Exception $e) {
//        return null;
    }

    //-------------------------------------------------

    /*Route::get('terms-of-service', function (){
        return redirect('https://www.powerwavelife.com/terms-and-conditions', 301);
    })->name('web.terms-of-service');

    Route::get('privacy-policy', function (){
        return redirect('https://www.powerwavelife.com/privacy-policy', 301);
    })->name('web.privacy-policy');*/

    Route::get('pusher', function (){
        return view('web.pusher');
    })
        //->middleware('check.ip')
        ->name('web.pusher');

    /*Route::get('sitemap{n?}.xml', 'SitemapController@index')
        ->where('n', '[0-9]+')
        ->name('web.sitemap-n');

    Route::get('sitemap.xml', 'SitemapController@index')
        ->name('web.sitemap');*/

    //-------------------------------------------------


    Route::get('blob-file/{model}', 'IndexController@blobFile')->name('blob.file');

    Route::get('', function (){
        return redirect()->route('redirect.after-login');
    })->name('home');

    Route::get('qr-code/{code}/{size?}', 'IndexController@qrCode')
        ->name('web.qr-code');

    Route::get('qr-code-image/{code}/{size?}{ext?}', 'IndexController@qrCodeImage')
        ->where('code', '[0-9a-zA-Z]+')
        ->where('size', '[0-9]+')
        ->where('ext', '[a-z.]+')
        ->name('web.qr-code-image');

    Route::post('webhook/cloudconvert', '\CloudConvert\Laravel\CloudConvertWebhooksController');
});
