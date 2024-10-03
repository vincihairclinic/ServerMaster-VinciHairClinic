<?php

namespace App\Repositories\Base;

use App\AppConf;
use App\Application;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Str;

class BlobRepository
{
    static function getImagesDeletedFromRequest($request, $storagePath = '', $fieldName = 'image')
    {
        $result = !empty($request->{'images_deleted_'.$fieldName}) ? (
        is_array($request->{'images_deleted_'.$fieldName}) ? $request->{'images_deleted_'.$fieldName} : explode(',', str_replace(route('blob.file', '').'/', '', $request->{'images_deleted_'.$fieldName}))
        ) : [];
        foreach ($result as $v => $i) {
            $result[$v] = trim($i);
        }
        return $result;
    }

    static function fileSave(Request $request = null, $fieldName = 'image', $type = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null, $file = null, $fileNameOrigin = null)
    {
        if (!empty($file) || ($fieldName = $fieldName ? ($request->hasFile($fieldName) ? $fieldName : false) : ($request->hasFile('file') ? 'file' : ($request->hasFile('image') ? 'image' : false)))) {
            if(!empty($file)){
                $fileName = $fileNameOrigin;
            }else{
                $file = $request->file($fieldName);
                if (is_array($file)) {
                    $file = array_shift($file);
                }
                $fileName = $file->hashName();
                $fileName = substr(pathinfo($fileName, PATHINFO_FILENAME).'_'.str_replace(' ', '-' , pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 145 - strlen(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION))).'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            }

            if($type == 'image'){
                if($imageEditAlgorithm){
                    if($imageEditAlgorithm == 'maxSize'){
                        $imageEditAlgorithmValue = !empty($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : 1000;
                        $imageEditAlgorithmValue = is_array($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : [$imageEditAlgorithmValue, $imageEditAlgorithmValue];

                        $image = Image::make($file)->orientate();
                        if($image->width() > $image->height() && $image->width() > $imageEditAlgorithmValue[0]){
                            $image = $image->widen($imageEditAlgorithmValue[0])->encode($imageFormat, 60);
                        }else if($image->width() < $image->height() && $image->height() > $imageEditAlgorithmValue[1]){
                            $image = $image->heighten($imageEditAlgorithmValue[1])->encode($imageFormat, 60);
                        }else{
                            $image = $image->encode($imageFormat, 60);
                        }
                    }else if(!empty($imageEditAlgorithmValue) && is_array($imageEditAlgorithmValue)){
                        $image = Image::make($file)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue[0], $imageEditAlgorithmValue[1])->encode($imageFormat, 60);
                    }else{
                        $image = Image::make($file)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue ? $imageEditAlgorithmValue : 1000)->encode($imageFormat, 60);
                    }
                }else if(!empty($imageFormat)) {
                    $image = Image::make($file)->orientate()->encode($imageFormat, 60);
                }else{
                    $image = Image::make($file)->orientate()->encode();
                }

                //File::create(['name' => $fileName, 'content' => $image->encoded, 'size' => strlen($image->encoded), 'mime_type' => $file->getClientMimeType()]);
                Storage::disk('image')->put($fileName, (string)$image);
            }else{
                //File::create(['name' => $fileName, 'content' => file_get_contents($file), 'size' => strlen(file_get_contents($file)), 'mime_type' => $file->getClientMimeType()]);
                Storage::disk('file')->putFileAs('', $file, $fileName);
            }
            return $fileName;
        }
        return null;
    }

    static function filesSave(Request $request, $fieldName = 'images', $type = 'image', $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $imageFormat = null)
    {
        $response = [];
        $files = (array)$request->file($fieldName);
        foreach ($files as $file){
            $fileName = $file->hashName();
            $fileName = substr(pathinfo($fileName, PATHINFO_FILENAME).'_'.str_replace(' ', '-' , pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 145 - strlen(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION))).'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            if($type == 'image'){
                if($imageEditAlgorithm){
                    if($imageEditAlgorithm == 'maxSize'){
                        $imageEditAlgorithmValue = !empty($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : 1000;
                        $imageEditAlgorithmValue = is_array($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : [$imageEditAlgorithmValue, $imageEditAlgorithmValue];

                        $image = Image::make($file)->orientate();
                        if($image->width() > $image->height() && $image->width() > $imageEditAlgorithmValue[0]){
                            $image = $image->widen($imageEditAlgorithmValue[0])->encode($imageFormat, 80);
                        }else if($image->width() < $image->height() && $image->height() > $imageEditAlgorithmValue[1]){
                            $image = $image->heighten($imageEditAlgorithmValue[1])->encode($imageFormat, 80);
                        }else{
                            $image = $image->encode($imageFormat, 80);
                        }
                    }else if(!empty($imageEditAlgorithmValue) && is_array($imageEditAlgorithmValue)){
                        $image = Image::make($file)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue[0], $imageEditAlgorithmValue[1])->encode($imageFormat, 80);
                    }else{
                        $image = Image::make($file)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue ? $imageEditAlgorithmValue : 1000)->encode($imageFormat, 80);
                    }
                }else if(!empty($imageFormat)) {
                    $image = Image::make($file)->orientate()->encode($imageFormat, 80);
                }else{
                    $image = Image::make($file)->orientate()->encode();
                }

                Storage::disk('image')->put($fileName, (string)$image);
                //File::create(['name' => $fileName, 'content' => $image->encoded, 'size' => strlen($image->encoded), 'mime_type' => $file->getClientMimeType()]);
            }else{
                Storage::disk('file')->putFileAs('', $file, $fileName);
                //File::create(['name' => $fileName, 'content' => file_get_contents($file), 'size' => strlen(file_get_contents($file)), 'mime_type' => $file->getClientMimeType()]);
            }
            $response[] = $fileName;
        }
        return $response;
    }

    static function getDatabaseFileName($url)
    {
        return urldecode(str_replace(route('blob.file', '').'/', '', $url));
    }

    static function getMediasDeletedFromRequest($request, $storagePath = '', $fieldName = 'image')
    {
        $result = !empty($request->{'urls_deleted_'.$fieldName}) ? (
        is_array($request->{'urls_deleted_'.$fieldName}) ? $request->{'urls_deleted_'.$fieldName} : explode(',', str_replace(route('blob.file', '').'/', '', $request->{'urls_deleted_'.$fieldName}))
        ) : [];

        foreach ($result as $v => $i) {
            $result[$v] = trim($i);
        }
        return $result;
    }

    static function filesDelete($urls = [], $type = 'image')
    {
        if(!empty($urls)){
            if(is_array($urls)){
                foreach ($urls as $url){
                    self::fileDelete($url, $type);
                }
            }else{
                self::fileDelete($urls, $type);
            }
        }
    }

    static function fileDelete($url = false, $type = 'image')
    {
        if(!empty(AppConf::$disableRemoveImage)){
            return;
        }
        if(!empty($url)){
            $file = File::where('name', urldecode(str_replace(route('blob.file', '').'/', '', $url)))->select('name')->first();
            if(!empty($file)){
                $file->delete();
            }
        }
    }

}