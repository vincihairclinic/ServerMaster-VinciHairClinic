<?php

namespace App\Repositories\Base;

use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class ImageRepository extends FileRepository
{
    static function saveImageFromUrl($url = null, $storagePath = 'image/', $imageName = null, $extension = null, $width = null, $height = null, $compression = null)
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
            return self::saveImage($image, $storagePath, $imageName, $extension, $width, $height, $compression);
        }

        return null;
    }

    static function saveImage($image = null, $storagePath = 'image/', $imageName = null, $extension = null, $width = null, $height = null, $compression = null)
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
                $image = Image::make($image)->orientate()->fit($width, $height)->encode($extension, !empty($compression) ? $compression : 80);
            }else if(!empty($height)){
                $image = Image::make($image)->orientate()->heighten($height)->encode($extension, !empty($compression) ? $compression : 80);
            }else if(!empty($width)){
                $image = Image::make($image)->orientate()->widen($width)->encode($extension, !empty($compression) ? $compression : 80);
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
}