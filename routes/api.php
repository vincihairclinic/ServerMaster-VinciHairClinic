<?php

use Illuminate\Support\Facades\Route;

Route::domain('api.'.env('APP_URL'))->group(function () {
    Route::group(['namespace' => 'Api', 'middleware' => ['json.request', 'convert.string.booleans', 'convert.id.null']], function () {

        Route::get('version', 'IndexController@apiVersion')
            ->name('api.version');

        //-----------------------------------------------------

        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', 'AuthController@login');
            Route::post('register', 'AuthController@register');
            Route::post('auto-register', 'AuthController@autoRegister');
            Route::post('forgot-password', 'AuthController@forgotPassword');
            Route::group(['middleware' => ['auth:api']], function () {
                Route::post('change-password', 'AuthController@changePassword');
            });
        });

        Route::get('localization', 'LocalizationController@index');
        Route::get('list', 'ListController@index');
        Route::post('user/simulation-requests-create', 'UserController@simulationRequestsCreate');


        Route::group(['middleware' => ['auth:api', 'set.locale', 'auth.access:'.\App\Models\Datasets\UserRole::USER]], function () {
            Route::post('user/bookning-consultation-create', 'UserController@bookningConsultationCreate');

            Route::get('list-after-login', 'ListController@listAfterLogin');

            Route::get('home', 'IndexController@home');

            Route::group(['prefix' => 'gh46dkeygf873fks'], function () {
                Route::post('email-verify', 'Gh46dkeygf873fksController@emailVerify');
            });

            Route::group(['prefix' => 'user'], function () {
                Route::get('', 'UserController@index');
                Route::post('edit', 'UserController@edit');
                Route::post('delete-account', 'UserController@deleteAccount');

                Route::post('email-verify', 'TmpController@emailVerify');
            });

            Route::group(['prefix' => 'article'], function () {
                Route::get('{article}', 'ArticleController@index')->where('article', '[0-9]+');
                Route::get('list/{articleCategory?}', 'ArticleController@list')->where('articleCategory', '[0-9]+');
                Route::get('next-list/{article}', 'ArticleController@nextList')->where('article', '[0-9]+');
                Route::get('list-categories', 'ArticleController@listCategories');
            });

            Route::group(['prefix' => 'podcast'], function () {
                Route::get('{podcast}', 'PodcastController@index')->where('podcast', '[0-9]+');
                Route::get('list', 'PodcastController@list');
            });

            Route::group(['prefix' => 'video'], function () {
                Route::get('list', 'VideoController@list');
            });

            Route::group(['prefix' => 'product'], function () {
                Route::get('{product}', 'ProductController@index')->where('product', '[0-9]+');
                Route::get('list/{productCategory?}', 'ProductController@list')->where('productCategory', '[0-9]+');
                Route::get('list-categories', 'ProductController@listCategories');
            });

            Route::post('upload/images', 'SystemController@uploadImages');
            Route::post('upload/files', 'SystemController@uploadFiles');

            Route::post('geocode/by-lat-lng', 'SystemController@geocodeByLatLng');
            Route::post('geocode/autocomplete', 'SystemController@geocodeAutocomplete');
        });

        //------------------------------------------------------

        Route::group(['middleware' => ['check.token']], function () {
            Route::get('status-get', 'IndexController@statusGet');
            Route::get('clear-view', 'IndexController@clearView')
                ->name('api.clear-view');
        });

    });
});
