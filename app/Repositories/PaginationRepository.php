<?php

namespace App\Repositories;

class PaginationRepository
{
    static $isNextPage = false;
    static $first = 0;
    static $back = false;
    static $countByPage = 12;
    static $page = 1;

    static function init()
    {
        PaginationRepository::$page = !empty(request()->page) && request()->page > 1 ? (int)request()->page : 1;
        PaginationRepository::$back = !empty(request()->back) && PaginationRepository::$page > 2 ? true : false;
        PaginationRepository::$first = !empty(request()->first) ? (int)request()->first : 0;

        return true;
    }

    static function addUrlParams($page = null, $back = null, $params = [])
    {
        if($page === '-1'){
            $page = PaginationRepository::$page - 1;
            $back = true;
        }else if($page === '+1'){
            $page = PaginationRepository::$page + 1;
        }

        $params = (array)$params;

        if(!empty($page)){
            if($page == 2){
                $params['page'] = $page;
            }else if($page > 2 && !empty(PaginationRepository::$first)){
                $params['page'] = $page;
                $params['first'] = PaginationRepository::$first;
                if(!empty($back)){
                    $params['back'] = 1;
                }
            }
        }

        return !empty($params) ? '?'.http_build_query(array_filter($params)) : '';
    }

    static function getModel($query)
    {
        $query = PaginationRepository::addQuery($query);
        $model = $query->get();
        $model = PaginationRepository::loadFromModel($model);
        return $model;
    }

    static function addQuery($query)
    {
        $offset = 0;
        if(PaginationRepository::$page == 2){
            $offset = PaginationRepository::$countByPage;
        }else if(!empty(PaginationRepository::$first)){
            if(PaginationRepository::$back){
                $query->where('id', '>', PaginationRepository::$first);
            }else{
                $offset = PaginationRepository::$countByPage - 1;
                $query->where('id', '<', PaginationRepository::$first);
            }
        }

        if(!empty($offset)){
            $query->offset($offset);
        }
        if(!PaginationRepository::$back){
            $query->orderBy('id', 'desc');
        }
        $query->limit(PaginationRepository::$countByPage + 1);


        return $query;
    }

    static function loadFromModel($model)
    {
        if($model->isEmpty()){
            return $model;
        }

        PaginationRepository::$isNextPage = $model->count() == PaginationRepository::$countByPage + 1;
        if(PaginationRepository::$isNextPage){
            $model = $model->slice(0, PaginationRepository::$countByPage);
        }
        if(PaginationRepository::$back){
            $model = $model->reverse();
        }
        PaginationRepository::$first = $model->first()->id;

        return $model;
    }

    static function responseAjax($html, $other = false)
    {
        $html = str_replace("\r", '', $html);
        $html = str_replace("\n", '', $html);
        $html = preg_replace("/\s\s+/", ' ', $html);
        return response(json_encode([
            'page' => PaginationRepository::$page,
            'isNextPage' => PaginationRepository::$isNextPage,
            'first' => PaginationRepository::$first,
            'other' => $other,
            'params' => PaginationRepository::addUrlParams('+1'),
            'html' => $html
        ], JSON_UNESCAPED_UNICODE), 200)->header('Content-Type', 'application/json');
    }
}