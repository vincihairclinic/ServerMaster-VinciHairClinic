<?php

namespace App\Repositories\Base;

use App\Models\Log;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class FileRepositoryOld
{
    static function getImagesDeletedFromRequest($request, $storagePath = '', $fieldName = 'image')
    {
        return !empty($request->{'images_deleted_'.$fieldName}) ? (
            is_array($request->{'images_deleted_'.$fieldName}) ? $request->{'images_deleted_'.$fieldName} : explode(',', str_replace(' ', '', str_replace(asset('storage').'/'.$storagePath, '', $request->{'images_deleted_'.$fieldName})))
        ) : [];
    }

    static function saveImageFromRequest(&$model, $request, $fieldName = 'image', $imageEditAlgorithm = 'widen', $imageEditAlgorithmValue = 1000, $imageFormat = null, $imagesDeleted = null, $storagePath = '', $thumbnailPath = null, $fileName = null)
    {
        $imagesDeleted = $imagesDeleted == null ? self::getImagesDeletedFromRequest($request, $storagePath, $fieldName) : $imagesDeleted;

        if(in_array($model->{$fieldName}, $imagesDeleted)){
            self::fileDelete($model->{$fieldName}, $storagePath);
            if($thumbnailPath != null){
                self::fileDelete($model->{$fieldName}, $thumbnailPath);
            }
            $model->{$fieldName} = null;
        }
        if(!empty($request->{$fieldName})){
            $model->{$fieldName} = self::getUrlImageSave($model->{$fieldName}, $request, $fieldName, $imageEditAlgorithm, $imageEditAlgorithmValue, $storagePath, $imageFormat, $fileName, $model->id);
            if($thumbnailPath != null) {
                self::getUrlImageSave(false, $request, $fieldName, 'fit', 200, $thumbnailPath, $imageFormat, $fileName, $model->id);
            }
        }
    }

    static function saveImagesFromRequest(&$model, $request, $fieldName = 'images', $imageEditAlgorithm = 'widen', $imageEditAlgorithmValue = 1000, $imageFormat = null, $imagesDeleted = null, $storagePath = '', $maxCountImages = 9)
    {
        $imagesDeleted = $imagesDeleted == null ? self::getImagesDeletedFromRequest($request, $storagePath, $fieldName) : $imagesDeleted;

        $images = (array)$model->{$fieldName};
        $countImages = count($images);

        foreach ($images as $i => $item){
            if(in_array($item, $imagesDeleted)){
                self::fileDelete($item, $storagePath);
                $images[$i] = '';
                $countImages--;
            }
        }
        if(!empty($request->{$fieldName})){
            $j = 1;
            foreach ($request->{$fieldName} as $i => $item){
                $j++;
                if($countImages >= $maxCountImages){
                    break;
                }
                if(isset($images[$i])){
                    $images[$i] = self::getUrlImageSave($images[$i], $request, [$fieldName, $i, $j], $imageEditAlgorithm, $imageEditAlgorithmValue, $storagePath, $imageFormat, null, $model->id);
                    $countImages++;
                }else{
                    $images[] = self::getUrlImageSave(false, $request, [$fieldName, $i, $j], $imageEditAlgorithm, $imageEditAlgorithmValue, $storagePath, $imageFormat, null, $model->id);
                    $countImages++;
                }
            }
        }

        $images = array_values(array_filter($images));

        $model->{$fieldName} = $images;
    }

    static function getUrlImageSave($model, Request $request, $fieldName, $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $storagePath = '', $imageFormat = null, $fileName = null, $modelId = null)
    {
        $imageUrl = self::imageSave($model, $request, $fieldName, $imageEditAlgorithm, $imageEditAlgorithmValue, $storagePath, $imageFormat, $fileName, $modelId);
        return $model && !$imageUrl ? (is_object($model) ? $model->{$fieldName} : $model) : ($imageUrl ? $imageUrl : null);
    }

    static function imageSave($model, Request $request, $fieldName, $imageEditAlgorithm = null, $imageEditAlgorithmValue = null, $storagePath = '', $imageFormat = null, $fileName = null, $modelId = null)
    {
        $imageUrl = null;
        $i = is_array($fieldName) ? $fieldName[1] : null;
        $j = is_array($fieldName) ? $fieldName[2] : null;
        $fieldName = is_array($fieldName) ? $fieldName[0] : $fieldName;
        if ($request->hasFile($fieldName)) {
            $image = $request->file($fieldName);
            if(is_array($image)){
                $image = array_values($image);
                $image = $image[0];
            }

            /*if($i !== null){
                $image = $image[$i];
            }*/
            if($model){
                if(is_object($model)){
                    self::fileDelete($model->{$fieldName}, $storagePath);
                }else{
                    self::fileDelete($model, $storagePath);
                }
            }

            if($imageFormat){
                if($fileName){
                    $imageUrl = $fileName.'.'.$imageFormat;
                }else{
                    if(!empty($modelId)){
                        if(!empty($i)){
                            $imageUrl = $modelId.'_'.$i.$j.'.'.$imageFormat;
                        }else{
                            $imageUrl = $modelId.'_'.time().'.'.$imageFormat;
                        }
                    }else{
                        $imageUrl = pathinfo($image->hashName(), PATHINFO_FILENAME).'.'.$imageFormat;
                    }
                }
            }else{
                if($fileName){
                    $imageUrl = $fileName.'.'.pathinfo($image->hashName(), PATHINFO_EXTENSION);
                }else{
                    if(!empty($modelId)){
                        if(!empty($i)){
                            $imageUrl = $modelId.'_'.$i.$j.'.'.pathinfo($image->hashName(), PATHINFO_EXTENSION);
                        }else{
                            $imageUrl = $modelId.'_'.time().'.'.pathinfo($image->hashName(), PATHINFO_EXTENSION);
                        }
                    }else{
                        $imageUrl = $image->hashName();
                    }
                }
            }

            if (extension_loaded('imagick')){
                Image::configure(['driver' => 'imagick']);
            }

            if($imageEditAlgorithm){
                if($imageEditAlgorithm == 'maxSize'){
                    $imageEditAlgorithmValue = !empty($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : 1000;
                    $imageEditAlgorithmValue = is_array($imageEditAlgorithmValue) ? $imageEditAlgorithmValue : [$imageEditAlgorithmValue, $imageEditAlgorithmValue];

                    $image = Image::make($image)->orientate();
                    if($image->width() > $image->height() && $image->width() > $imageEditAlgorithmValue[0]){
                        $image = $image->widen($imageEditAlgorithmValue[0])->encode($imageFormat, 60);
                    }else if($image->width() < $image->height() && $image->height() > $imageEditAlgorithmValue[1]){
                        $image = $image->heighten($imageEditAlgorithmValue[1])->encode($imageFormat, 60);
                    }else{
                        $image = $image->encode($imageFormat, 60);
                    }
                }else if(!empty($imageEditAlgorithmValue) && is_array($imageEditAlgorithmValue)){
                    $image = Image::make($image)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue[0], $imageEditAlgorithmValue[1])->encode($imageFormat, 60);
                }else{
                    $image = Image::make($image)->orientate()->{$imageEditAlgorithm}($imageEditAlgorithmValue ? $imageEditAlgorithmValue : 1000)->encode($imageFormat, 60);
                }
            }else{
                $image = Image::make($image)->orientate()->encode($imageFormat, 60);
            }

            Storage::disk('public')->put($storagePath.$imageUrl, (string)$image);
        }

        return $imageUrl;
    }

    //----------------------------------------

    static function filesDelete($urls, $storagePath = '')
    {
        if(!empty($urls)){
            foreach ($urls as $url){
                self::fileDelete($url, $storagePath);
            }
        }
    }

    static function fileDelete($url = false, $storagePath = '')
    {
        if(!empty($url)){
            $storageFilePath = config('filesystems.disks.public.root').'/'.$storagePath.self::getFileNameFromUrl($url);

            if(file_exists($storageFilePath)){
                unlink($storageFilePath);
            }
        }
    }

    static function getRandomName($extension = 'jpg')
    {
        return str_replace('.', '_', (9999999999-(time()+microtime(true)))).'.'.$extension;
    }

    static function getFileNameFromUrl($url)
    {
        return pathinfo($url, PATHINFO_BASENAME);
    }

    static function downloadFromUrl($url, $contentTypes = [], $recall = 0)
    {
        try{
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_BUFFERSIZE, 128);
            curl_setopt($curl, CURLOPT_NOPROGRESS, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, function(
                $DownloadSize, $Downloaded, $UploadSize, $Uploaded
            ){
                return ($Downloaded > (2 * 1000000)) ? 1 : 0;
            });
            $file = curl_exec($curl);

            $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);

            curl_close($curl);

            if(!empty($contentTypes)){
                if(!in_array($contentType, $contentTypes)){
                    return null;
                }
            }

            return $file;
        } catch (\Exception $e) {
            if(!$recall || $recall < 10){
                sleep(1);
                return self::downloadFromUrl($url, $contentTypes, $recall+1);
            }
        }

        return null;
    }

    static function saveImageFromUrl($url = null, $storagePath = '', $imageName = null, $width = null, $height = null, $extension = null, $compression = null)
    {
        if(!empty($url)){
            $image = self::downloadFromUrl($url, [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
            ]);
            if(empty($image)){
                return null;
            }
            $extension = !empty($extension) ? $extension : pathinfo($url, PATHINFO_EXTENSION);
            return self::saveImage($image, $storagePath, $imageName, $width, $height, $extension, $compression);
        }

        return null;
    }

    static function saveImage($image = null, $storagePath = '', $imageName = null, $width = null, $height = null, $extension = null, $compression = null)
    {
        if(empty($image)){
            return null;
        }

        $extension = !empty($extension) ? $extension : 'jpg';
        $imageUrl = !empty($imageName) ? $imageName.'.'.$extension : self::getRandomName($extension);

        if (extension_loaded('imagick')){
            Image::configure(['driver' => 'imagick']);
        }

        if(in_array($extension, ['jpg', 'gif', 'png'])){
            if(!empty($width) && !empty($height)){
                $image = Image::make($image)->orientate()->fit($width, $height)->encode($extension, !empty($compression) ? $compression : 70);
            }else if(!empty($height)){
                $image = Image::make($image)->orientate()->heighten($height)->encode($extension, !empty($compression) ? $compression : 70);
            }else if(!empty($width)){
                $image = Image::make($image)->orientate()->widen($width)->encode($extension, !empty($compression) ? $compression : 70);
            }else if(!empty($compression)){
                $image = Image::make($image)->orientate()->encode($extension, $compression);
            }
        }

        try{
            Storage::disk('public')->put($storagePath.$imageUrl, (string)$image);
            return $imageUrl;
        } catch (\Exception $e) {
            return null;
        }
    }

    static function getImageSize($imageUrl)
    {
        if(empty($imageUrl)){
            return null;
        }

        $imageData = getimagesize($imageUrl);
        return (object)[
            'url' => $imageUrl,
            'width' => $imageData[0],
            'height' => $imageData[1],
        ];
    }
}