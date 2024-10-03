<?php

namespace App\Repositories\Base;

use App\AppConf;
use App\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Str;

class FileRepository
{
    static function fileSaveApi($request, $type = 'image')
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            return CurlRepository::sendCurl(str_replace(config('app.url'), AppConf::$storage_url, route('api.file-save')), [
                'file' => curl_file_create($file->getRealPath()),
                'type' => $type,
                'token' => config('app.token'),
            ], 'post');
        }
        return '';
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
                $fileName = substr(pathinfo($fileName, PATHINFO_FILENAME).'_'.str_replace('-', '-' , pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 145 - strlen(pathinfo($fileName, PATHINFO_EXTENSION))).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
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

                Storage::disk('image')->put($fileName, (string)$image);
            }else{
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
            $fileName = substr(pathinfo($fileName, PATHINFO_FILENAME).'_'.str_replace('-', '-' , pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 0, 145 - strlen(pathinfo($fileName, PATHINFO_EXTENSION))).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
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
            }else{
                Storage::disk('file')->putFileAs('', $file, $fileName);
            }
            $response[] = $fileName;
        }
        return $response;
    }

    //-------------------------------

    static function filesDeleteApi($urls = [], $type = 'image')
    {
        CurlRepository::sendCurl(str_replace(config('app.url'), AppConf::$storage_url, route('api.files-delete')), http_build_query([
            'urls' => $urls,
            'type' => $type,
            'token' => config('app.token'),
        ]), 'post');
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
            $storageFilePath = public_path().'/storage/'.$type.'/'.FileRepository::getStorageFilePath($url);
            if(file_exists($storageFilePath)){
                unlink($storageFilePath);
            }
        }
    }

    //----------------------------------

    static function getImageNameFromUrl($url)
    {
        if(empty($url)){
            return null;
        }
        if(mb_strpos($url, '/') === false){
            return $url;
        }
        $url = explode('/', $url);
        $url = $url[count($url)-1];
        $url = mb_strpos($url, '.') !== false ? $url : null;
        return $url;
    }

    //----------------------------------

    /*static function parseStorageFileUrl($url)
    {
        $type = null;
        $name = $url;

        $url = parse_url($url, PHP_URL_PATH);
        $url = str_replace('/storage/', '', $url);
        $url = explode('/', $url);
        if(count($url) == 2){
            $type = $url[0];
            $name = $url[1];
        }

        $fileInfo = pathinfo($name);
        if(!empty($fileInfo['extension'])){
            $fileInfo['extension'] = strtolower($fileInfo['extension']);
        }
        return [
            'type' => !empty($type) ? $type : (!empty($fileInfo) && !empty($fileInfo['extension']) && in_array($fileInfo['extension'], ['png', 'jpeg', 'jpg', 'gif', 'svg']) ? 'image' : 'file'),
            'name' => $name,
        ];
    }*/

    static function getImagesDeletedFromRequest($request, $storagePath = '', $fieldName = 'image')
    {
        $result = !empty($request->{'images_deleted_'.$fieldName}) ? (
        is_array($request->{'images_deleted_'.$fieldName}) ? $request->{'images_deleted_'.$fieldName} : explode(',', str_replace(asset('storage').'/'.$storagePath, '', $request->{'images_deleted_'.$fieldName}))
        ) : [];
        foreach ($result as $v => $i) {
            $result[$v] = trim($i);
        }
        return $result;
    }

    static function getMediasDeletedFromRequest($request, $storagePath = '', $fieldName = 'image')
    {
        $result = !empty($request->{'urls_deleted_'.$fieldName}) ? (
        is_array($request->{'urls_deleted_'.$fieldName}) ? $request->{'urls_deleted_'.$fieldName} : explode(',', str_replace(asset('storage').'/'.$storagePath, '', $request->{'urls_deleted_'.$fieldName}))
        ) : [];
        foreach ($result as $v => $i) {
            $result[$v] = trim($i);
        }
        return $result;
    }

    static function generateFilePath($originFileName, $randToken, $csrfToken, $validationExt = null)
    {
        $ext = pathinfo($originFileName, PATHINFO_EXTENSION);
        $originFileName = pathinfo($originFileName, PATHINFO_FILENAME);
        if(!empty($validationExt)){
            if($validationExt == 'image'){
                $ext = strtolower($ext);
                if(!in_array($ext, ['png', 'jpeg', 'jpg', 'gif', 'svg'])){
                    return false;
                }
            }
        }
        return substr(md5($originFileName.$randToken.$csrfToken), 0, 2).'/'.substr(md5($originFileName.$randToken.$csrfToken), 2).(!empty($ext) ? '.'.$ext : '');
    }

    static function getStorageDir($url)
    {
        $dirName = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_DIRNAME);
        if(!empty($dirName) && !in_array($dirName, ['/', '.'])){
            $dirName = str_replace('storage/image', '', $dirName);
            $dirName = str_replace('storage/file', '', $dirName);
            $dirName = str_replace('storage', '', $dirName);
            $dirName = str_replace('image', '', $dirName);
            $dirName = str_replace('file', '', $dirName);
            $dirName = str_replace('//', '/', $dirName);
            $dirName = str_replace('//', '/', $dirName);
            $dirName = Str::replaceFirst('/', '', $dirName);
            $dirName = $dirName == '/' ? '' : $dirName;
            return $dirName;
        }
        return '';
    }

    static function getStorageFilePath($url, &$fileInfo =null)
    {
        $fileInfo = pathinfo($url);
        if(!empty($fileInfo['extension'])){
            $fileInfo['extension'] = strtolower(explode('?', $fileInfo['extension'])[0]);
        }
        $result = FileRepository::getStorageDir($url).'/'.$fileInfo['filename'].(!empty($fileInfo['extension']) ? '.'.$fileInfo['extension'] : '');
        $result = Str::replaceFirst('/', '', $result);
        $result = $result == '/' ? '' : $result;
        return $result;
    }

    //----------------------------------

    static function getimagesize($imageUrl)
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

    static function getRandomName($extension = 'jpg')
    {
        return str_replace('.', '_', (9999999999-(time()+microtime(true)))).'.'.$extension;
    }
}