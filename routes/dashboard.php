<?php

use App\Models\Datasets\ListModels;
use Illuminate\Support\Facades\Route;

Route::domain('dashboard.'.env('APP_URL'))->group(function () {
    Route::group([/*'middleware' => ['check.ip']*/], function () {


        /*Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('logout', function (){
            return redirect()->route('dashboard.redirect.after-login');
        });*/

        Route::group(['middleware' => ['auth', 'auth.access:'.\App\Models\Datasets\UserRole::ADMIN], 'namespace' => 'Dashboard'], function () {
            Route::get('layouts',function (){
                return view('dashboard.layouts.content');
            });

            Route::get('redirect/after-login', function (){
                return redirect()->route('dashboard.user.index');
            })->name('dashboard.redirect.after-login');

            Route::post('setting/update', 'IndexController@settingUpdate')->name('dashboard.setting.update');

            Route::group(['middleware' => ['auth.access:'.\App\Models\Datasets\UserRole::ADMIN]], function () {
                Route::post('update-setting/{model?}', 'IndexController@updateSetting')
                    ->name('dashboard.update-setting');

                //--------------------------------------------------------------------

                if (count(array_keys(ListModels::$data)) > 0) {
                    Route::group(['prefix' => '{settingsListModels}'], function () {
                        Route::get('', 'ListModelController@index')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.index');
                        Route::post('index-json/', 'ListModelController@indexJson')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.index-json');
                        Route::get('create', 'ListModelController@create')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.create');
                        Route::post('store', 'ListModelController@store')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.store');
                        Route::get('{model}/edit', 'ListModelController@edit')
                            ->where('model', '[0-9]+')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.edit');
                        Route::post('{model}/update', 'ListModelController@update')
                            ->where('model', '[0-9]+')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.update');
                        Route::post('{model}/destroy', 'ListModelController@destroy')
                            ->where('model', '[0-9]+')
                            ->where(['settingsListModels' => implode('|', array_keys(ListModels::$data))])
                            ->name('dashboard.list-models.destroy');
                    });
                }
                try {
                Route::group(['prefix' => 'page'], function () {
                    Route::get('{model}', 'PageController@index')
                        ->where('model', implode('|', \App\Models\Page::pluck('route')->toArray()))
                        ->name('dashboard.page.index');
                    Route::post('{model}/update', 'PageController@update')
                        ->where('model', implode('|', \App\Models\Page::pluck('route')->toArray()))
                        ->name('dashboard.page.update');
                });
                } catch (Exception $e) {
//                    return null;
                }

                Route::group(['prefix' => 'user'], function () {
                    Route::get('', 'UserController@index')
                        ->name('dashboard.user.index');
                    Route::post('index-json/', 'UserController@indexJson')
                        ->name('dashboard.user.index-json');
                    Route::get('create', 'UserController@create')
                        ->name('dashboard.user.create');
                    Route::post('store', 'UserController@store')
                        ->name('dashboard.user.store');
                    Route::get('{model}/edit', 'UserController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user.edit');
                    Route::post('{model}/update', 'UserController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user.update');
                    Route::post('{model}/destroy', 'UserController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user.destroy');
                });

                Route::group(['prefix' => 'language'], function () {
                    Route::get('', 'LanguageController@index')
                        ->name('dashboard.language.index');
                    Route::post('index-json/', 'LanguageController@indexJson')
                        ->name('dashboard.language.index-json');
                    Route::get('create', 'LanguageController@create')
                        ->name('dashboard.language.create');
                    Route::post('store', 'LanguageController@store')
                        ->name('dashboard.language.store');
                    Route::get('{model}/edit', 'LanguageController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.language.edit');
                    Route::post('{model}/update', 'LanguageController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.language.update');
                    Route::post('sort-update', 'LanguageController@sortUpdate')
                        ->name('dashboard.language.sort-update');
                    Route::post('{model}/destroy', 'LanguageController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.language.destroy');
                });

                Route::group(['prefix' => 'procedure'], function () {
                    Route::get('', 'ProcedureController@index')
                        ->name('dashboard.procedure.index');
                    Route::post('index-json/', 'ProcedureController@indexJson')
                        ->name('dashboard.procedure.index-json');
                    Route::get('create', 'ProcedureController@create')
                        ->name('dashboard.procedure.create');
                    Route::post('store', 'ProcedureController@store')
                        ->name('dashboard.procedure.store');
                    Route::get('{model}/edit', 'ProcedureController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure.edit');
                    Route::post('{model}/update', 'ProcedureController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure.update');
                    Route::post('sort-update', 'ProcedureController@sortUpdate')
                        ->name('dashboard.procedure.sort-update');
                    Route::post('{model}/destroy', 'ProcedureController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure.destroy');
                });

                Route::group(['prefix' => 'procedure-result'], function () {
                    Route::get('', 'ProcedureResultController@index')
                        ->name('dashboard.procedure-result.index');
                    Route::post('index-json/', 'ProcedureResultController@indexJson')
                        ->name('dashboard.procedure-result.index-json');
                    Route::get('create', 'ProcedureResultController@create')
                        ->name('dashboard.procedure-result.create');
                    Route::post('store', 'ProcedureResultController@store')
                        ->name('dashboard.procedure-result.store');
                    Route::get('{model}/edit', 'ProcedureResultController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result.edit');
                    Route::post('{model}/update', 'ProcedureResultController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result.update');
                    Route::post('sort-update', 'ProcedureResultController@sortUpdate')
                        ->name('dashboard.procedure-result.sort-update');
                    Route::post('{model}/destroy', 'ProcedureResultController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result.destroy');
                });

                Route::group(['prefix' => 'procedure-result-image'], function () {
                    Route::get('', 'ProcedureResultImageController@index')
                        ->name('dashboard.procedure-result-image.index');
                    Route::post('index-json/{model}', 'ProcedureResultImageController@indexJson')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-image.index-json');
                    Route::get('create', 'ProcedureResultImageController@create')
                        ->name('dashboard.procedure-result-image.create');
                    Route::post('store', 'ProcedureResultImageController@store')
                        ->name('dashboard.procedure-result-image.store');
                    Route::get('{model}/edit', 'ProcedureResultImageController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-image.edit');
                    Route::post('{model}/update', 'ProcedureResultImageController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-image.update');
                    Route::post('sort-update', 'ProcedureResultImageController@sortUpdate')
                        ->name('dashboard.procedure-result-image.sort-update');
                    Route::post('{model}/destroy', 'ProcedureResultImageController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-image.destroy');
                });

                Route::group(['prefix' => 'procedure-result-video'], function () {
                    Route::get('', 'ProcedureResultVideoController@index')
                        ->name('dashboard.procedure-result-video.index');
                    Route::post('index-json/{model}', 'ProcedureResultVideoController@indexJson')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-video.index-json');
                    Route::get('create', 'ProcedureResultVideoController@create')
                        ->name('dashboard.procedure-result-video.create');
                    Route::post('store', 'ProcedureResultVideoController@store')
                        ->name('dashboard.procedure-result-video.store');
                    Route::get('{model}/edit', 'ProcedureResultVideoController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-video.edit');
                    Route::post('{model}/update', 'ProcedureResultVideoController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-video.update');
                    Route::post('sort-update', 'ProcedureResultVideoController@sortUpdate')
                        ->name('dashboard.procedure-result-video.sort-update');
                    Route::post('{model}/destroy', 'ProcedureResultVideoController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.procedure-result-video.destroy');
                });


                Route::group(['prefix' => 'country'], function () {
                    Route::get('', 'CountryController@index')
                        ->name('dashboard.country.index');
                    Route::post('index-json/', 'CountryController@indexJson')
                        ->name('dashboard.country.index-json');
                    Route::get('create', 'CountryController@create')
                        ->name('dashboard.country.create');
                    Route::post('store', 'CountryController@store')
                        ->name('dashboard.country.store');
                    Route::get('{model}/edit', 'CountryController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.country.edit');
                    Route::post('{model}/update', 'CountryController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.country.update');
                    Route::post('sort-update', 'CountryController@sortUpdate')
                        ->name('dashboard.country.sort-update');
//                    Route::post('{model}/destroy', 'CountryController@destroy')
//                        ->where('model', '[0-9]+')
//                        ->name('dashboard.country.destroy');
                });


                Route::group(['prefix' => 'territory'], function () {
                    Route::get('', 'TerritoryController@index')
                        ->name('dashboard.territory.index');
                    Route::post('index-json/', 'TerritoryController@indexJson')
                        ->name('dashboard.territory.index-json');
                    Route::get('create', 'TerritoryController@create')
                        ->name('dashboard.territory.create');
                    Route::post('store', 'TerritoryController@store')
                        ->name('dashboard.territory.store');
                    Route::get('{model}/edit', 'TerritoryController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.territory.edit');
                    Route::post('{model}/update', 'TerritoryController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.territory.update');
                    Route::post('sort-update', 'TerritoryController@sortUpdate')
                        ->name('dashboard.territory.sort-update');
//                    Route::post('{model}/destroy', 'CountryController@destroy')
//                        ->where('model', '[0-9]+')
//                        ->name('dashboard.country.destroy');
                });

                Route::group(['prefix' => 'clinic'], function () {
                    Route::get('', 'ClinicController@index')
                        ->name('dashboard.clinic.index');
                    Route::post('index-json/', 'ClinicController@indexJson')
                        ->name('dashboard.clinic.index-json');
                    Route::get('create', 'ClinicController@create')
                        ->name('dashboard.clinic.create');
                    Route::post('store', 'ClinicController@store')
                        ->name('dashboard.clinic.store');
                    Route::get('{model}/edit', 'ClinicController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.clinic.edit');
                    Route::post('{model}/update', 'ClinicController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.clinic.update');
                    Route::post('sort-update', 'ClinicController@sortUpdate')
                        ->name('dashboard.clinic.sort-update');
                    Route::post('{model}/destroy', 'ClinicController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.clinic.destroy');
                });

                Route::group(['prefix' => 'hair-loss-type'], function () {
                    Route::get('', 'HairLossTypeController@index')
                        ->name('dashboard.hair-loss-type.index');
                    Route::post('index-json/', 'HairLossTypeController@indexJson')
                        ->name('dashboard.hair-loss-type.index-json');
                    Route::get('create', 'HairLossTypeController@create')
                        ->name('dashboard.hair-loss-type.create');
                    Route::post('store', 'HairLossTypeController@store')
                        ->name('dashboard.hair-loss-type.store');
                    Route::get('{model}/edit', 'HairLossTypeController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-loss-type.edit');
                    Route::post('{model}/update', 'HairLossTypeController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-loss-type.update');
                    Route::post('sort-update', 'HairLossTypeController@sortUpdate')
                        ->name('dashboard.hair-loss-type.sort-update');
                    Route::post('{model}/destroy', 'HairLossTypeController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-loss-type.destroy');
                });

                Route::group(['prefix' => 'hair-type'], function () {
                    Route::get('', 'HairTypeController@index')
                        ->name('dashboard.hair-type.index');
                    Route::post('index-json/', 'HairTypeController@indexJson')
                        ->name('dashboard.hair-type.index-json');
                    Route::get('create', 'HairTypeController@create')
                        ->name('dashboard.hair-type.create');
                    Route::post('store', 'HairTypeController@store')
                        ->name('dashboard.hair-type.store');
                    Route::get('{model}/edit', 'HairTypeController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-type.edit');
                    Route::post('{model}/update', 'HairTypeController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-type.update');
                    Route::post('sort-update', 'HairTypeController@sortUpdate')
                        ->name('dashboard.hair-type.sort-update');
                    Route::post('{model}/destroy', 'HairTypeController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.hair-type.destroy');
                });

                Route::group(['prefix' => 'localization'], function () {
                    Route::get('', 'LocalizationController@index')
                        ->name('dashboard.localization.index');
                    Route::post('index-json/', 'LocalizationController@indexJson')
                        ->name('dashboard.localization.index-json');
                    Route::get('create', 'LocalizationController@create')
                        ->name('dashboard.localization.create');
                    Route::post('store', 'LocalizationController@store')
                        ->name('dashboard.localization.store');
                    Route::get('{model}/edit', 'LocalizationController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.localization.edit');
                    Route::post('{model}/update', 'LocalizationController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.localization.update');
                    Route::post('{model}/destroy', 'LocalizationController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.localization.destroy');
                });

                Route::group(['prefix' => 'article'], function () {
                    Route::get('', 'ArticleController@index')
                        ->name('dashboard.article.index');
                    Route::post('index-json/', 'ArticleController@indexJson')
                        ->name('dashboard.article.index-json');
                    Route::get('create', 'ArticleController@create')
                        ->name('dashboard.article.create');
                    Route::post('store', 'ArticleController@store')
                        ->name('dashboard.article.store');
                    Route::get('{model}/edit', 'ArticleController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article.edit');
                    Route::post('{model}/update', 'ArticleController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article.update');
                    Route::post('sort-update', 'ArticleController@sortUpdate')
                        ->name('dashboard.article.sort-update');
                    Route::post('{model}/destroy', 'ArticleController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article.destroy');
                });

                Route::group(['prefix' => 'article-category'], function () {
                    Route::get('', 'ArticleCategoryController@index')
                        ->name('dashboard.article-category.index');
                    Route::post('index-json/', 'ArticleCategoryController@indexJson')
                        ->name('dashboard.article-category.index-json');
                    Route::get('create', 'ArticleCategoryController@create')
                        ->name('dashboard.article-category.create');
                    Route::post('store', 'ArticleCategoryController@store')
                        ->name('dashboard.article-category.store');
                    Route::get('{model}/edit', 'ArticleCategoryController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article-category.edit');
                    Route::post('{model}/update', 'ArticleCategoryController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article-category.update');
                    Route::post('sort-update', 'ArticleCategoryController@sortUpdate')
                        ->name('dashboard.article-category.sort-update');
                    Route::post('{model}/destroy', 'ArticleCategoryController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.article-category.destroy');
                });

                Route::group(['prefix' => 'product-category'], function () {
                    Route::get('', 'ProductCategoryController@index')
                        ->name('dashboard.product-category.index');
                    Route::post('index-json/', 'ProductCategoryController@indexJson')
                        ->name('dashboard.product-category.index-json');
                    Route::get('create', 'ProductCategoryController@create')
                        ->name('dashboard.product-category.create');
                    Route::post('store', 'ProductCategoryController@store')
                        ->name('dashboard.product-category.store');
                    Route::get('{model}/edit', 'ProductCategoryController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-category.edit');
                    Route::post('{model}/update', 'ProductCategoryController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-category.update');
                    Route::post('sort-update', 'ProductCategoryController@sortUpdate')
                        ->name('dashboard.product-category.sort-update');
                    Route::post('{model}/destroy', 'ProductCategoryController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-category.destroy');
                });

                Route::group(['prefix' => 'product'], function () {
                    Route::get('', 'ProductController@index')
                        ->name('dashboard.product.index');
                    Route::post('index-json/', 'ProductController@indexJson')
                        ->name('dashboard.product.index-json');
                    Route::get('create', 'ProductController@create')
                        ->name('dashboard.product.create');
                    Route::post('store', 'ProductController@store')
                        ->name('dashboard.product.store');
                    Route::get('{model}/edit', 'ProductController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product.edit');
                    Route::post('{model}/update', 'ProductController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product.update');
                    Route::post('sort-update', 'ProductController@sortUpdate')
                        ->name('dashboard.product.sort-update');
                    Route::post('{model}/destroy', 'ProductController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product.destroy');
                });

                Route::group(['prefix' => 'product-review'], function () {
                    Route::get('', 'ProductReviewController@index')
                        ->name('dashboard.product-review.index');
                    Route::post('index-json/{model}', 'ProductReviewController@indexJson')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-review.index-json');
                    Route::get('create', 'ProductReviewController@create')
                        ->name('dashboard.product-review.create');
                    Route::post('store', 'ProductReviewController@store')
                        ->name('dashboard.product-review.store');
                    Route::get('{model}/edit', 'ProductReviewController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-review.edit');
                    Route::post('{model}/update', 'ProductReviewController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-review.update');
                    Route::post('sort-update', 'ProductReviewController@sortUpdate')
                        ->name('dashboard.product-review.sort-update');
                    Route::post('{model}/destroy', 'ProductReviewController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.product-review.destroy');
                });


                Route::group(['prefix' => 'podcast'], function () {
                    Route::get('', 'PodcastController@index')
                        ->name('dashboard.podcast.index');
                    Route::post('index-json/', 'PodcastController@indexJson')
                        ->name('dashboard.podcast.index-json');
                    Route::get('create', 'PodcastController@create')
                        ->name('dashboard.podcast.create');
                    Route::post('store', 'PodcastController@store')
                        ->name('dashboard.podcast.store');
                    Route::get('{model}/edit', 'PodcastController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast.edit');
                    Route::post('{model}/update', 'PodcastController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast.update');
                    Route::post('sort-update', 'PodcastController@sortUpdate')
                        ->name('dashboard.podcast.sort-update');
                    Route::post('{model}/destroy', 'PodcastController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast.destroy');
                });

                Route::group(['prefix' => 'podcast-episode'], function () {
                    Route::get('', 'PodcastEpisodeController@index')
                        ->name('dashboard.podcast-episode.index');
                    Route::post('index-json', 'PodcastEpisodeController@indexJson')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast-episode.index-json');
                    Route::get('create', 'PodcastEpisodeController@create')
                        ->name('dashboard.podcast-episode.create');
                    Route::post('store', 'PodcastEpisodeController@store')
                        ->name('dashboard.podcast-episode.store');
                    Route::get('{model}/edit', 'PodcastEpisodeController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast-episode.edit');
                    Route::post('{model}/update', 'PodcastEpisodeController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast-episode.update');
                    Route::post('sort-update', 'PodcastEpisodeController@sortUpdate')
                        ->name('dashboard.podcast-episode.sort-update');
                    Route::post('{model}/destroy', 'PodcastEpisodeController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.podcast-episode.destroy');
                });


                Route::group(['prefix' => 'book'], function () {
                    Route::get('', 'BookController@index')
                        ->name('dashboard.book.index');
                    Route::post('index-json/', 'BookController@indexJson')
                        ->name('dashboard.book.index-json');
                    Route::get('create', 'BookController@create')
                        ->name('dashboard.book.create');
                    Route::post('store', 'BookController@store')
                        ->name('dashboard.book.store');
                    Route::get('{model}/edit', 'BookController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book.edit');
                    Route::post('{model}/update', 'BookController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book.update');
                    Route::post('sort-update', 'BookController@sortUpdate')
                        ->name('dashboard.book.sort-update');
                    Route::post('{model}/destroy', 'BookController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book.destroy');
                });


                Route::group(['prefix' => 'book-review'], function () {
                    Route::get('', 'BookReviewController@index')
                        ->name('dashboard.book-review.index');
                    Route::post('index-json/{book}', 'BookReviewController@indexJson')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-review.index-json');
                    Route::get('create', 'BookReviewController@create')
                        ->name('dashboard.book-review.create');
                    Route::post('store', 'BookReviewController@store')
                        ->name('dashboard.book-review.store');
                    Route::get('{model}/edit', 'BookReviewController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-review.edit');
                    Route::post('{model}/update', 'BookReviewController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-review.update');
                    Route::post('sort-update', 'BookReviewController@sortUpdate')
                        ->name('dashboard.book-review.sort-update');
                    Route::post('{model}/destroy', 'BookReviewController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-review.destroy');
                });


                Route::group(['prefix' => 'book-information'], function () {
                    Route::get('', 'BookInformationController@index')
                        ->name('dashboard.book-information.index');
                    Route::post('index-json/{book}', 'BookInformationController@indexJson')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-information.index-json');
                    Route::get('create', 'BookInformationController@create')
                        ->name('dashboard.book-information.create');
                    Route::post('store', 'BookInformationController@store')
                        ->name('dashboard.book-information.store');
                    Route::get('{model}/edit', 'BookInformationController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-information.edit');
                    Route::post('{model}/update', 'BookInformationController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-information.update');
                    Route::post('sort-update', 'BookInformationController@sortUpdate')
                        ->name('dashboard.book-information.sort-update');
                    Route::post('{model}/destroy', 'BookInformationController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-information.destroy');
                });

                Route::group(['prefix' => 'book-pre-instruction'], function () {
                    Route::get('', 'BookInstructionController@index')
                        ->name('dashboard.book-pre-instruction.index');
                    Route::post('index-json/{book}', 'BookInstructionController@indexJsonPreInstruction')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-pre-instruction.index-json');
                    Route::post('store', 'BookInstructionController@storePreInstruction')
                        ->name('dashboard.book-pre-instruction.store');
                    Route::get('{model}/edit', 'BookInstructionController@editPreInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-instruction.edit');
                    Route::post('{model}/update', 'BookInstructionController@updatePreInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-instruction.update');
                    Route::post('sort-update', 'BookInstructionController@sortUpdatePreInstruction')
                        ->name('dashboard.book-pre-instruction.sort-update');
                    Route::post('{model}/destroy', 'BookInstructionController@destroyPreInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-instruction.destroy');
                });

                Route::group(['prefix' => 'book-post-instruction'], function () {
                    Route::get('', 'BookInstructionController@index')
                        ->name('dashboard.book-post-instruction.index');
                    Route::post('index-json/{book}', 'BookInstructionController@indexJsonPostInstruction')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-post-instruction.index-json');
                    Route::post('store', 'BookInstructionController@storePostInstruction')
                        ->name('dashboard.book-post-instruction.store');
                    Route::get('{model}/edit', 'BookInstructionController@editPostInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-instruction.edit');
                    Route::post('{model}/update', 'BookInstructionController@updatePostInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-instruction.update');
                    Route::post('sort-update', 'BookInstructionController@sortUpdatePostInstruction')
                        ->name('dashboard.book-post-instruction.sort-update');
                    Route::post('{model}/destroy', 'BookInstructionController@destroyPostInstruction')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-instruction.destroy');
                });

                Route::group(['prefix' => 'book-pre-additional'], function () {
                    Route::get('', 'BookAdditionalController@index')
                        ->name('dashboard.book-pre-additional.index');
                    Route::post('index-json/{book}', 'BookAdditionalController@indexJsonPreAdditional')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-pre-additional.index-json');
                    Route::post('store', 'BookAdditionalController@storePreAdditional')
                        ->name('dashboard.book-pre-additional.store');
                    Route::get('{model}/edit', 'BookAdditionalController@editPreAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional.edit');
                    Route::post('{model}/update', 'BookAdditionalController@updatePreAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional.update');
                    Route::post('sort-update', 'BookAdditionalController@sortUpdatePreAdditional')
                        ->name('dashboard.book-pre-additional.sort-update');
                    Route::post('{model}/destroy', 'BookAdditionalController@destroyPreAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional.destroy');
                });

                Route::group(['prefix' => 'book-post-additional'], function () {
                    Route::get('', 'BookAdditionalController@index')
                        ->name('dashboard.book-post-additional.index');
                    Route::post('index-json/{book}', 'BookAdditionalController@indexJsonPostAdditional')
                        ->where('book', '[0-9]+')
                        ->name('dashboard.book-post-additional.index-json');
                    Route::post('store', 'BookAdditionalController@storePostAdditional')
                        ->name('dashboard.book-post-additional.store');
                    Route::get('{model}/edit', 'BookAdditionalController@editPostAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional.edit');
                    Route::post('{model}/update', 'BookAdditionalController@updatePostAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional.update');
                    Route::post('sort-update', 'BookAdditionalController@sortUpdatePostAdditional')
                        ->name('dashboard.book-post-additional.sort-update');
                    Route::post('{model}/destroy', 'BookAdditionalController@destroyPostAdditional')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional.destroy');
                });

                Route::group(['prefix' => 'book-pre-additional-item'], function () {
                    Route::get('', 'BookAdditionalItemController@index')
                        ->name('dashboard.book-pre-additional-item.index');
                    Route::post('index-json/{additional}', 'BookAdditionalItemController@indexJsonPreAdditionalItem')
                        ->where('additional', '[0-9]+')
                        ->name('dashboard.book-pre-additional-item.index-json');
                    Route::post('store', 'BookAdditionalItemController@storePreAdditionalItem')
                        ->name('dashboard.book-pre-additional-item.store');
                    Route::get('{model}/edit', 'BookAdditionalItemController@editPreAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional-item.edit');
                    Route::post('{model}/update', 'BookAdditionalItemController@updatePreAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional-item.update');
                    Route::post('sort-update', 'BookAdditionalItemController@sortUpdatePreAdditionalItem')
                        ->name('dashboard.book-pre-additional-item.sort-update');
                    Route::post('{model}/destroy', 'BookAdditionalItemController@destroyPreAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-pre-additional-item.destroy');
                });

                Route::group(['prefix' => 'book-post-additional-item'], function () {
                    Route::get('', 'BookAdditionalController@index')
                        ->name('dashboard.book-post-additional-item.index');
                    Route::post('index-json/{additional}', 'BookAdditionalItemController@indexJsonPostAdditionalItem')
                        ->where('additional', '[0-9]+')
                        ->name('dashboard.book-post-additional-item.index-json');
                    Route::post('store', 'BookAdditionalItemController@storePostAdditionalItem')
                        ->name('dashboard.book-post-additional-item.store');
                    Route::get('{model}/edit', 'BookAdditionalItemController@editPostAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional-item.edit');
                    Route::post('{model}/update', 'BookAdditionalItemController@updatePostAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional-item.update');
                    Route::post('sort-update', 'BookAdditionalItemController@sortUpdatePostAdditionalItem')
                        ->name('dashboard.book-post-additional-item.sort-update');
                    Route::post('{model}/destroy', 'BookAdditionalItemController@destroyPostAdditionalItem')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-post-additional-item.destroy');
                });


                Route::group(['prefix' => 'user-simulation-request'], function () {
                    Route::get('', 'UserSimulationRequestController@index')
                        ->name('dashboard.user-simulation-request.index');
                    Route::post('index-json/', 'UserSimulationRequestController@indexJson')
                        ->name('dashboard.user-simulation-request.index-json');
                    Route::get('create', 'UserSimulationRequestController@create')
                        ->name('dashboard.user-simulation-request.create');
                    Route::post('store', 'UserSimulationRequestController@store')
                        ->name('dashboard.user-simulation-request.store');
                    Route::get('{model}/edit', 'UserSimulationRequestController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user-simulation-request.edit');
                    Route::post('{model}/update', 'UserSimulationRequestController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user-simulation-request.update');
                    Route::post('sort-update', 'UserSimulationRequestController@sortUpdate')
                        ->name('dashboard.user-simulation-request.sort-update');
                    Route::post('{model}/destroy', 'UserSimulationRequestController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.user-simulation-request.destroy');
                });

                Route::group(['prefix' => 'book-consultation'], function () {
                    Route::get('', 'BookConsultationController@index')
                        ->name('dashboard.book-consultation.index');
                    Route::post('index-json/', 'BookConsultationController@indexJson')
                        ->name('dashboard.book-consultation.index-json');
                    Route::get('{model}/edit', 'BookConsultationController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-consultation.edit');
                    Route::post('{model}/update', 'BookConsultationController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.book-consultation.update');
                });

                Route::group(['prefix' => 'request-contact-from-clinic'], function () {
                    Route::get('', 'RequestContactFromClinicController@index')
                        ->name('dashboard.request-contact-from-clinic.index');
                    Route::post('index-json/', 'RequestContactFromClinicController@indexJson')
                        ->name('dashboard.request-contact-from-clinic.index-json');
                    Route::get('{model}/edit', 'RequestContactFromClinicController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.request-contact-from-clinic.edit');
                    Route::post('{model}/update', 'RequestContactFromClinicController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.request-contact-from-clinic.update');
                });


                Route::group(['prefix' => 'simulation-request-type'], function () {
                    Route::get('', 'SimulationRequestTypeController@index')
                        ->name('dashboard.simulation-request-type.index');
                    Route::post('index-json/', 'SimulationRequestTypeController@indexJson')
                        ->name('dashboard.simulation-request-type.index-json');
                    Route::get('create', 'SimulationRequestTypeController@create')
                        ->name('dashboard.simulation-request-type.create');
                    Route::post('store', 'SimulationRequestTypeController@store')
                        ->name('dashboard.simulation-request-type.store');
                    Route::get('{model}/edit', 'SimulationRequestTypeController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.simulation-request-type.edit');
                    Route::post('{model}/update', 'SimulationRequestTypeController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.simulation-request-type.update');
                    Route::post('sort-update', 'SimulationRequestTypeController@sortUpdate')
                        ->name('dashboard.simulation-request-type.sort-update');
                    Route::post('{model}/destroy', 'SimulationRequestTypeController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.simulation-request-type.destroy');
                });


                Route::group(['prefix' => 'video'], function () {
                    Route::get('', 'VideoController@index')
                        ->name('dashboard.video.index');
                    Route::post('index-json/', 'VideoController@indexJson')
                        ->name('dashboard.video.index-json');
                    Route::get('{model}/edit', 'VideoController@edit')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.video.edit');
                    Route::post('{model}/update', 'VideoController@update')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.video.update');
                    Route::post('{model}/destroy', 'VideoController@destroy')
                        ->where('model', '[0-9]+')
                        ->name('dashboard.video.destroy');
                });


                Route::group(['prefix' => 'file'], function () {
                    Route::get('/edit}', 'FileController@edit')
                        ->name('dashboard.file.edit');
                    Route::post('/update}', 'FileController@update')
                        ->name('dashboard.file.update');
                });
                try {
                    Route::group(['prefix' => 'setting-link'], function () {
                        Route::get('/edit/{model}', 'SettingsLinkController@edit')
                            ->where('model', \App\Models\Datasets\SettingsLink::implodeIds('|'))
                            ->name('dashboard.settings-link.edit');
                        Route::post('/update/{model}', 'SettingsLinkController@update')
                            ->where('model',  \App\Models\Datasets\SettingsLink::implodeIds('|'))
                            ->name('dashboard.settings-link.update');
                    });
                } catch (Exception $e) {

                }


                //--------------------------------------------------------------------

                Route::group(['middleware' => ['check.key']], function () {
                    Route::get('clear-view', 'IndexController@clearView')->name('dashboard.clear-view');
                });
                Route::group(['prefix' => 'console-proc'], function () {
                    Route::get('{procId?}/{procNum?}', 'ConsoleProcController@index')
                        ->where('procId', '[0-9]+')
                        ->where('procNum', '[0-9]+')
                        ->name('dashboard.console-proc.index');

                    Route::group(['middleware' => ['check.key']], function () {
                        Route::get('{procId}/{procNum}/total-result', 'ConsoleProcController@totalResult')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.total-result');
                        Route::get('{procId}/{procNum}/reset-last-id', 'ConsoleProcController@resetLastId')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.reset-last-id');
                        Route::get('{procId}/{procNum}/reset-status-proc', 'ConsoleProcController@resetStatusProc')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.reset-status-proc');
                        Route::get('{procId}/{procNum}/remove-data-proc', 'ConsoleProcController@removeDataProc')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.remove-data-proc');
                        Route::get('{procId}/{procNum}/start', 'ConsoleProcController@start')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.start');
                        Route::get('{procId}/{procNum}/stop', 'ConsoleProcController@stop')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.stop');
                        Route::get('{procId}/{procNum}/clear-log', 'ConsoleProcController@clearLog')
                            ->where('procId', '[0-9]+')
                            ->where('procNum', '[0-9]+')
                            ->name('dashboard.console-proc.clear-log');
                    });
                });

            });

        });
    });
});
