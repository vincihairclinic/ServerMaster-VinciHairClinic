<?php

namespace App\Repositories\Base;

use App\Repositories\BaseRepository;

class YandexDictionaryRepository
{
    static $yandexDictionaryKeys = [
        'dict.1.1.20190821T002104Z.a9d84970c8f0eaa9.5eb4bc7c3916f75258ec8d747416430d514c32c5',
        'dict.1.1.20190820T095238Z.db6e22f76930d8c1.a7f0d9e61876fde8a76620719e7da73e2ff99a96',
        'dict.1.1.20190821T002600Z.a5068cbd63e81757.0d6f3b90a17a0453028ab7233c0395981ea655b8',
        'dict.1.1.20190821T002712Z.e681b6bc13761b55.b343146ec9c6d92c0376d2fa7a857eb25064b14b',
        'dict.1.1.20190821T003034Z.4bd84108b4f30745.c9d73dac36cefa5f5bc4e653887ac8ff8b2702b8',
        'dict.1.1.20190821T003244Z.5a89ed06cd9d423d.28c543d82cf43e98bb53392833f196659002a0a5',
        'dict.1.1.20190821T003421Z.4c3e5e3b4e2a546b.9cc43d8d348b127049fd9b6eaa7cba118282394e',
        'dict.1.1.20190821T003651Z.79c3024259e45102.d98aac200491d08aaff20485d2c8f5b35c3fcabe',
        'dict.1.1.20190821T003800Z.902eb07dc9fc20d4.62ea164bb029e3aad5ca9695dc16a2523eb3bcf8',
        'dict.1.1.20190821T003908Z.56012d946c2bf487.1a51725a115e957ebee3e6d23da594c9af909d3c',
        'dict.1.1.20190821T004110Z.f933abf727da22b8.2345305cf8b1ec93d4bf7b5e4d910a4f07c8628d',
        'dict.1.1.20190821T004214Z.4c119e6ee78c2d39.5d6c2ca600c6eb699230e4dbcd1861b8e326c3d8',
        'dict.1.1.20190821T004322Z.8e47d2a3a3e828ca.cdd4076c803c9d34ceeda2b740bbac05b78d1f5f',
        'dict.1.1.20190821T135441Z.b1032669ec1f27a4.366a25bc710a70c2741f3bdf895b5112e3da6cc8',
        'dict.1.1.20190821T135600Z.8ce7a6eda5c11908.2bce9779a6ecc0feddce541439c4db7763ebf82a',
        'dict.1.1.20190821T135707Z.9aeb9bcf61f512b7.6346443738f5395c97408466c9f2b4c01110262a',
        'dict.1.1.20190821T135826Z.d0476954b93c3c76.edda418b52aa0314b6bee3cfa72ff64da274266e',
        'dict.1.1.20190821T135917Z.c8c7bcaaede259da.993cb5d1e7b81dccd0b4efbb65b6841d516d8fab',
        'dict.1.1.20190821T135941Z.2283d82e36fff29f.32b8196e71922205350d97e5ab6364422142e6ff',

        //-------------------------------------------

        'dict.1.1.20190823T185705Z.3dcd8a6b262091e3.64ba91bbfe9bfecc171bd0a15885f6da8072212e',
        'dict.1.1.20190823T204045Z.2c678fc7fd42d563.3ae122df66964bdb9b53380142e01442d56c69b7',
        'dict.1.1.20190823T204244Z.4a8534fa0fc6be60.9f409e7a11f5e6238272848daac66ec8d04f361c',
        'dict.1.1.20190823T204410Z.cdc1c0536ca5a52c.a345a33223066c3b7feab990f91344b8722e87fd',
        'dict.1.1.20190823T204732Z.f643c4d9817b7dce.8b53261011a08d5e929edf8ad54ac326a82d498b',

        'dict.1.1.20190823T204905Z.fcabe5f09885c7f7.174211f9c8c995e88ada48a9bae43ee4c5005bf1',
        'dict.1.1.20190823T205022Z.113f6ce6c16579ec.a953797c166fc7d4c7cc5a0699ec3654c5d307ee',
        'dict.1.1.20190823T205207Z.c3216618354d9904.9ec43183926bb9db16443aaff3da11ef2fe78fa0',
        'dict.1.1.20190823T205315Z.9d0527116bfe6140.063d7a7d15be3b9c65ec140b7786f6d8e51e205d',
        'dict.1.1.20190823T205443Z.f7a9d121901e0e42.a12e0e782bd4282d62871cb0c32c25e40893760f',

        'dict.1.1.20190823T205828Z.48eac0c888352100.387ba555b0221add85e8408123991da2d78e3f33',
        'dict.1.1.20190823T205956Z.47dbfb5709d7e936.85bf0b91b39b2a0c711c80c713f3fd81a05e5078',
        'dict.1.1.20190823T210149Z.8b179fb2e275d0f1.2d7c1a052babfebdbeafa619872d19639a655797',
        'dict.1.1.20190823T210249Z.605f9d42adceed12.91134f50c4cd9840928c032d4f53b2f078be59b1',
        'dict.1.1.20190823T210431Z.a95392905c3cbed1.a0e25eadf3751670d9c6deaff365140e8c3fcd64',

        'dict.1.1.20190823T210545Z.78aff6a05f36075e.ad51bd81d87a7a242800d75e63e0f10cbb9dd9b4',
        'dict.1.1.20190823T210652Z.04679bec585567d1.2115c53044e47bf0d0812fada9dfca781e51e833',
        'dict.1.1.20190823T210804Z.c36e9b0b93d633ac.0a4d5399f974af908ae18327e60611a07d853a26',
        'dict.1.1.20190823T210904Z.e9b9c31269bbef72.59fa7dfc5d55c8d8cdd58c200102bfbf920f7257',
        'dict.1.1.20190823T211045Z.af1e3f5489f9ea72.e4faf274fb1ba9b84486ec60128b834bd4b20d06',

        'dict.1.1.20190823T211242Z.af7abb95babdf8e4.50c7b513a94e7719f30f029b8ee6288a81c07d6b',
        'dict.1.1.20190823T211343Z.7b97b5ae61f0f619.1b6a6e5c88049a72bad0cb86506252737bb2109e',
        'dict.1.1.20190823T211453Z.dacb1f46736ee6bd.54beef12748880e56c21e3064bcd3a0a36af5435',
        'dict.1.1.20190823T211603Z.d69cefaadc51eda4.4f099c2c5708491d8313511582ad634dd7e57347',
        'dict.1.1.20190823T211702Z.c491e0fae64b714b.3609ee957e8556abbc5f1c724eb12674afd7ea97',

        'dict.1.1.20190823T211813Z.7243422827f8f630.7b6ea8abf96dc5feb73eb1e8aa972da19cdb4d92',
        'dict.1.1.20190823T211939Z.9ccb0d6a33ad7ff0.4f5ae4977575346366b6f87fcc4439c8870468a0',
        'dict.1.1.20190823T212228Z.e4c3490f02694dd5.766b04d85339d8a83e27869861446d6321c9c1af',
        'dict.1.1.20190823T212352Z.1bfac59e029edba8.f856fdc4ec9515fe4bca509d90c916986c287c15',
        'dict.1.1.20190823T212518Z.c9bd0835da04e4d9.d13e64460ec9855965111fcf59d2e318e066c573',

        'dict.1.1.20190824T154127Z.52f733b70548b35f.47cdd33b9f8a70de4820988eaec0f327f6e17695',
        'dict.1.1.20190827T194701Z.415817d4ed14263f.81281d5ce040e360d03eea49deaffe2ad961934d',
    ];

    static function get($word, $lang)
    {
        $response = self::send($word, $lang);
        return self::transformResponse($response, $word);
    }

    static function transformResponse($response, $word = null)
    {
        $result = [$word, []];
        if(BaseRepository::notEmptyVar($response, 'def.0.text')) {
            $def = $response->def[0];
            $resultTmp = [];
            if(!empty($def->tr)){
                $result[0] = $def->text;
                foreach ($def->tr as $def_tr) {
                    $syn = [];
                    if(BaseRepository::notEmptyVar($def_tr, 'syn')){
                        foreach ($def_tr->syn as $def_tr_syn){
                            if(BaseRepository::notEmptyVar($def_tr_syn, 'text')){
                                $syn[] = $def_tr_syn->text;
                            }
                        }
                    }
                    $resultTmp[] = [$def_tr->text, !empty($syn) ? $syn : []];
                }
            }
            $result[1] = $resultTmp;
        }

        return $result;
    }

    static function getKey()
    {
        return YandexDictionaryRepository::$yandexDictionaryKeys[rand(0, count(YandexDictionaryRepository::$yandexDictionaryKeys)-1)];
    }

    static function send($text, $lang = 'ru', $recall = 0)
    {
        $response = [];
        $originText = $text;

        if(empty($text)){
            return $response;
        }

        if(!empty($lang) && !$recall){
            $lang = 'lang='.$lang.'-'.$lang.'&';
        }

        try{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?key='.self::getKey().'&'.$lang.'text='.urlencode($text));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);

            curl_close($ch);

            $response = json_decode($response);

            if(!empty($response->code) && $response->code == 403){
                if(app()->runningInConsole()){
                    sleep(10);
                }else{
                    $response = null;
                }
            }
        } catch (\Exception $e) {
            $response = null;
        }

        if($response === null && $recall <= 30 && app()->runningInConsole()){
            $response = self::send($originText, $lang, $recall+1);
        }

        if(empty($response)){
            $response = [];
        }

        return $response;
    }
}