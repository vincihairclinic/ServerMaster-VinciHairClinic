<?php

namespace App\Repositories\Base;

use App\Models\Article;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\Video;
use Carbon\Carbon;

class YoutubeRepository
{
    //  1//09VtU64x9oA3SCgYIARAAGAkSNwF-L9IrGwyGz2ccvX0wdVGB28B3yAb67s7sFAPyhvsnx6JWuhhXIN3D-stG3difEdI-qtqBPJ8

    static function run()
    {
        if ($client = self::getGoogleClient()) {

            $youtubeService = new \Google_Service_YouTube($client);


            $channelsResponse = $youtubeService->channels->listChannels('contentDetails, snippet', [
                'id' => 'UCRCdgbOalJkM4y4GwZvIoYA',
                //'id' => 'UCdR7911aGvMvAjOEe0tJ8zA',
                //'id' => 'UCF7VJc8K6Kg_d8tfrHgt-vg',
            ]);


            if(!empty($channelsResponse->items) && !empty($channelsResponse->items[0])){
                $channelItem = $channelsResponse->items[0];
                if($channelItem->snippet){
                    if(!empty($channelItem->snippet->title)){
                        //Channel Name
                        Setting::updateValue('youtube_channel_name', $channelItem->snippet->title);
                    }
                    if(!empty($channelItem->snippet->thumbnails->medium->url)){
                        //https://yt3.ggpht.com/ytc/AAUvwninW2ShFWQFF5tqD1_ueOc3KMxlDdHYyQ2PZvV91Q=s88-c-k-c0x00ffffff-no-rj
                        Setting::updateValue('url_youtube_channel_image', $channelItem->snippet->thumbnails->medium->url);
                    }
                }
            }

            /*try {
                foreach (self::getScheduledLiveStreamItems($youtubeService, $channelsResponse) as $item){
                    if(!$video = Video::where('source', $item->source)->first()){
                        $video = new Video();
                        $video->is_live_stream = true;
                        $video->published_at = !empty($item->published_at) ? Carbon::parse($item->published_at)->format('Y-m-d H:i:s') : null;
                        //$video->scheduled_at = Carbon::parse($item->scheduled_at)->format('Y-m-d H:i:s');
                        $video->source = $item->source;
                        $video->title = $item->title;
                        $video->save();
                    }
                }
            }catch (\Exception $e){}*/


            /*
            foreach (self::getPlaylistItems($youtubeService, $channelsResponse) as $item){
                if(!$video = Video::where('source', $item->source)->first()){
                    $video = new Video();
                    $video->scheduled_at = !empty($video->scheduled_at) && Carbon::parse($video->scheduled_at) > Carbon::now() ? $video->scheduled_at : null;
                    $video->published_at = empty($video->scheduled_at) ? Carbon::parse($item->published_at)->format('Y-m-d H:i:s') : null;
                    $video->source = $item->source;
                    $video->title = $item->title;
                    if($video->save()){
                        $video->tags()->sync(Tag::getIdsByNames($item->tags));
                        if(!empty($video->published_at)){
                            if(!Article::where('video_id', $video->id)->first()){
                                Article::create([
                                    'video_id' => $video->id
                                ]);
                            }
                        }
                    }
                }else{
                    $video->scheduled_at = !empty($video->scheduled_at) && Carbon::parse($video->scheduled_at) > Carbon::now() ? $video->scheduled_at : null;
                    $video->published_at = empty($video->scheduled_at) ? Carbon::parse($item->published_at)->format('Y-m-d H:i:s') : null;
                    $video->source = $item->source;
                    $video->title = $item->title;
                    if($video->save()){
                        $video->tags()->sync(Tag::getIdsByNames($item->tags));
                        if(!empty($video->published_at)){
                            if(!Article::where('video_id', $video->id)->first()){
                                Article::create([
                                    'video_id' => $video->id
                                ]);
                            }
                        }
                    }
                }
            }*/

            //1//09VtU64x9oA3SCgYIARAAGAkSNwF-L9IrGwyGz2ccvX0wdVGB28B3yAb67s7sFAPyhvsnx6JWuhhXIN3D-stG3difEdI-qtqBPJ8

            foreach (self::getPlaylistItems($youtubeService, $channelsResponse) as $item){
                if(!empty($item->published_at)){
                    if(!$video = Video::where('source', $item->source)->first()){
                        $video = new Video();
                    }

                    $video->published_at = !empty($item->published_at) ? Carbon::parse($item->published_at)->format('Y-m-d H:i:s') : null;
                    $video->source = $item->source;
                    $video->title = $item->title;

                    if($video->save()){
                        //$video->tags()->sync(Tag::getIdsByNames($item->tags));
                        /*if(!empty($video->published_at)){
                            if(!Article::where('video_id', $video->id)->first()){
                                Article::create([
                                    'video_id' => $video->id
                                ]);
                            }
                        }*/
                    }
                }
            }
        }
    }

    static function getVideoTags(\Google_Service_YouTube $youtubeService, $videoId)
    {
        $tagNames = [];

        if ($response = $youtubeService->videos->listVideos('snippet', [
            'id' => $videoId,
        ])) {
            if (!empty($response['items']) && !empty($response['items'][0]) && !empty($response['items'][0]['snippet']) && !empty($response['items'][0]['snippet']['tags'])) {
                $tagNames = $response['items'][0]['snippet']['tags'];
            }
        }
        return $tagNames;
    }


    static function getPlaylistItems(\Google_Service_YouTube $youtubeService, $channelsResponse)
    {
        /*$playlistIds = [];
        $response = $youtubeService->playlists->listPlaylists('snippet', [
            'channelId' => 'UCF7VJc8K6Kg_d8tfrHgt-vg',
            'maxResults' => 50
        ]);
        foreach ($response['items'] as $r) {
            $playlistIds[] = $r['id'];
        }


        $items = [];
        foreach ($playlistIds as $playlistId) {
            $response = $youtubeService->videos->listVideos('snippet', [
                'maxResults' => 50
            ]);
            dd($response, 1);
            $response = $youtubeService->playlistItems->listPlaylistItems('snippet', [
                'playlistId' => $playlistId,
                'maxResults' => 50
            ]);
            //dd($response['items']); //TODO
            foreach ($response['items'] as $r) {

                array_push($items, (object)[
                    'source' => $r['snippet']['resourceId']['videoId'],
                    'title' => $r['snippet']['title'],
                    'published_at' => $r['snippet']['publishedAt'],
                    'scheduled_at' => null,
                    'tags' => self::getVideoTags($youtubeService, $r['snippet']['resourceId']['videoId']),
                ]);
            }
        }*/

        $items = [];
        foreach ($channelsResponse['items'] as $channel) {
            /*$nextPageToken = null;
            while (true){
                $response = $youtubeService->playlistItems->listPlaylistItems('snippet,status,contentDetails', [
                    'playlistId' => $channel['contentDetails']['relatedPlaylists']['uploads'],
                    'maxResults' => 50,
                    'pageToken' => $nextPageToken,
                ]);
                $nextPageToken = $response->nextPageToken;
                if(empty($nextPageToken)){
                    break;
                }

                foreach ($response['items'] as $r) {
                    if($r['status']['privacyStatus'] != 'private'){
                        array_push($items, (object)[
                            'source' => $r['snippet']['resourceId']['videoId'],
                            'title' => $r['snippet']['title'],
                            'published_at' => $r['snippet']['publishedAt'],
                            'scheduled_at' => null,
                            'tags' => self::getVideoTags($youtubeService, $r['snippet']['resourceId']['videoId']),
                        ]);
                    }
                }
            }*/

            $response = $youtubeService->playlistItems->listPlaylistItems('snippet,status,contentDetails', [
                'playlistId' => $channel['contentDetails']['relatedPlaylists']['uploads'],
                'maxResults' => 50,
            ]);

            foreach ($response['items'] as $r) {
                if($r['status']['privacyStatus'] != 'private'){
                    array_push($items, (object)[
                        'source' => $r['snippet']['resourceId']['videoId'],
                        'title' => $r['snippet']['title'],
                        'published_at' => $r['snippet']['publishedAt'],
                        'scheduled_at' => null,
                        //'tags' => self::getVideoTags($youtubeService, $r['snippet']['resourceId']['videoId']),
                    ]);
                }
            }

        }

        return $items;
    }

    static function getScheduledLiveStreamItems($youtubeService, $channelsResponse)
    {
        $items = [];
        foreach ($channelsResponse['items'] as $channel) {
            $response = $youtubeService->liveBroadcasts->listLiveBroadcasts('snippet', [
                'mine'       => true,
                'maxResults' => 4
            ]);
            foreach ($response['items'] as $r) {
                array_push($items, (object)[
                    'source' => $r['id'],
                    'title' => $r['snippet']['title'],
                    'scheduled_at' => $r['snippet']['scheduledStartTime'],
                    'published_at' => $r['snippet']['publishedAt'],
                ]);
            }
        }
        return $items;
    }

    //-----------------------------------------------------

    static function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setClientId(env('GOOGLE_YOUTUBE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_YOUTUBE_CLIENT_SECRET'));

        if ($token = $client->fetchAccessTokenWithRefreshToken(Setting::getValue('youtube_refresh_token'))) {
            if (!empty($token['access_token']) && !empty($token['refresh_token'])) {
                Setting::updateValue('youtube_refresh_token', $token['refresh_token']);

                $client->setAccessToken($token['access_token']);
                return $client;

            }
        }

        return false;
    }

}
