<?php

namespace App\Repositories\Base;

class BaseControllerRepository
{
    static function loadToModelBase(&$model, $request, $fields = [], $updateForce = false, $save = false)
    {
        if(empty($model)){
            return false;
        }
        foreach ($fields as $field){
            if($updateForce){
                $model->{$field} = $request->{$field};
            }else{
                $model->{$field} = !$request->has($field) ? $model->{$field} : $request->{$field};
            }
        }
        if($save){
            return $model->save();
        }
    }

    static function loadToModelBool(&$model, $request, $fields = [])
    {
        if(empty($model)){
            return false;
        }

        foreach ($fields as $field){
            $model->{$field} = $request->{$field} ? 1 : 0;
        }
    }

    static function loadToModelImages(&$model, $request)
    {
        if(empty($model)){
            return false;
        }

        $deleteImages = [];
        foreach (FileRepository::getImagesDeletedFromRequest($request, 'image/', 'images') as $url){
            FileRepository::filesDelete($url);
            $deleteImages[] = FileRepository::getStorageFilePath($url);
        }

        $images = $model->images;
        foreach ($images as $i => $image){
            if(in_array($image, $deleteImages)){
                $images[$i] = null;
            }
        }

        $images = array_merge($images, FileRepository::filesSave($request));
        $model->images = array_values(array_filter($images));
    }

    static function loadToModelImage(&$model, $request, $field = 'image')
    {
        if(empty($model)){
            return false;
        }

        foreach (FileRepository::getImagesDeletedFromRequest($request, 'image/', $field) as $url){
            FileRepository::filesDelete($url);
            if($model->{$field} == FileRepository::getStorageFilePath($url)){
                $model->{$field} = null;
            }
        }
        $imageName = FileRepository::fileSave($request, $field, 'image', 'maxSize', 1024);
        $model->{$field} =  !empty($imageName) ? $imageName : $model->{$field};
    }

    static function loadToModelImagesBlob(&$model, $request, $field = 'images')
    {
        if(empty($model)){
            return false;
        }

        $deleteImages = [];
        foreach (BlobRepository::getImagesDeletedFromRequest($request, 'image/', $field) as $url){
            BlobRepository::filesDelete($url);
            $deleteImages[] = BlobRepository::getDatabaseFileName($url);
        }

        $images = $model->{$field};
        foreach ($images as $i => $image){
            if(in_array($image, $deleteImages)){
                $images[$i] = null;
            }
        }

        $images = array_merge($images, BlobRepository::filesSave($request, $field));
        $model->{$field} = array_values(array_filter($images));
    }

    static function loadToModelImageBlob(&$model, $request, $field = 'image')
    {
        if(empty($model)){
            return false;
        }

        foreach (BlobRepository::getImagesDeletedFromRequest($request, 'image/', $field) as $url){
            BlobRepository::filesDelete($url);
            if($model->{$field} == BlobRepository::getDatabaseFileName($url)){
                $model->{$field} = null;
            }
        }

        $imageName = BlobRepository::fileSave($request, $field, 'image', 'maxSize', 1024);
        $model->{$field} =  !empty($imageName) ? $imageName : $model->{$field};
    }

    static function loadToModelMedias(&$model, $request, $field = 'images', $mediaType = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null)
    {
        if(empty($model)){
            return false;
        }

        $deleteMedias = [];
        foreach (FileRepository::getMediasDeletedFromRequest($request, ($mediaType == 'image' ? 'image/' : 'file/'), $field) as $url){
            FileRepository::filesDelete($url, $mediaType);
            $deleteMedias[] = FileRepository::getStorageFilePath($url);
        }

        $medias = $model->{$field};
        foreach ($medias as $i => $media){
            if(in_array($media, $deleteMedias)){
                $medias[$i] = null;
            }
        }

        $medias = array_merge($medias, FileRepository::filesSave($request, $field, $mediaType, $imageEditAlgorithm, $imageEditAlgorithmValue, $imageFormat));
        $model->{$field} = array_values(array_filter($medias));

        return true;
    }

    static function loadToModelMediasBlob(&$model, $request, $field = 'images', $mediaType = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null)
    {
        if(empty($model)){
            return false;
        }

        $deleteMedias = [];
        foreach (BlobRepository::getMediasDeletedFromRequest($request, ($mediaType == 'image' ? 'image/' : 'file/'), $field) as $url){
            BlobRepository::filesDelete($url, $mediaType);
            $deleteMedias[] = BlobRepository::getDatabaseFileName($url);
        }

        $medias = $model->{$field};
        foreach ($medias as $i => $media){
            if(in_array($media, $deleteMedias)){
                $medias[$i] = null;
            }
        }

        $medias = array_merge($medias, BlobRepository::filesSave($request, $field, $mediaType, $imageEditAlgorithm, $imageEditAlgorithmValue, $imageFormat));
        $model->{$field} = array_values(array_filter($medias));

        return true;
    }

    static function loadToModelMedia(&$model, $request, $field = 'image', $mediaType = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null)
    {
        if(empty($model)){
            return false;
        }

        foreach (FileRepository::getMediasDeletedFromRequest($request, ($mediaType == 'image' ? 'image/' : 'file/'), $field) as $url){
            FileRepository::filesDelete($url, $mediaType);
            if($model->{$field} == FileRepository::getStorageFilePath($url)){
                $model->{$field} = null;
            }
        }

        //$imageName = FileRepository::fileSave($request, $field, $mediaType, $imageEditAlgorithm, $imageEditAlgorithmValue, $imageFormat);
        $imageName = FileRepository::fileSave($request, $field, $mediaType, 'maxSize', 1024, $imageFormat);
        $model->{$field} =  !empty($imageName) ? $imageName : $model->{$field};

        return true;
    }

    static function loadToModelMediaBlob(&$model, $request, $field = 'image', $mediaType = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null)
    {
        if(empty($model)){
            return false;
        }

        foreach (BlobRepository::getMediasDeletedFromRequest($request, ($mediaType == 'image' ? 'image/' : 'file/'), $field) as $url){
            BlobRepository::filesDelete($url, $mediaType);
            if($model->{$field} == BlobRepository::getDatabaseFileName($url)){
                $model->{$field} = null;
            }
        }

        $imageName = BlobRepository::fileSave($request, $field, $mediaType, 'maxSize', 1024, $imageFormat);
        $model->{$field} =  !empty($imageName) ? $imageName : $model->{$field};

        return true;
    }
}