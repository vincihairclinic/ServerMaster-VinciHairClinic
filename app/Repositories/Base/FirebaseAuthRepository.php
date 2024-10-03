<?php

namespace App\Repositories\Base;

use App\AppConf;
use Exception;

class FirebaseAuthRepository
{
    static $password = 'kjsDkasf23we';
    static $idToken = null;
    static $email = null;

    static function send($action, $data = [], $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://identitytoolkit.googleapis.com/v1/'.$action.'?key='.AppConf::$firebase_auth_key);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json',
        ], $headers));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);
        try {
            $response = json_decode($response);
        }catch (Exception $e){
            $response = false;
        }
        return $response;
    }

    static function getIdToken($email = false, $signUp = true)
    {
        if(!empty(self::$idToken) && empty($email)){
            return self::$idToken;
        }
        if(!self::signIn($email)){
            if($signUp){
                self::signUp($email);
            }
        }
        return self::$idToken;
    }

    /*------------------------------------------*/

    static function signUp($email = null)
    {
        self::$email = !empty($email) ? $email : self::$email;
        if($response = self::send('accounts:signUp', [
            'email' => self::$email,
            'password' => self::$password,
            'returnSecureToken' => true
        ])){
            if(!empty($response->idToken)){
                self::$idToken = $response->idToken;
                return $response->idToken;
            }
        }
        return false;
    }

    static function signIn($email = null)
    {
        self::$email = !empty($email) ? $email : self::$email;
        if($response = self::send('accounts:signInWithPassword', [
            'email' => self::$email,
            'password' => self::$password,
            'returnSecureToken' => true
        ])){
            if(!empty($response->idToken)){
                self::$idToken = $response->idToken;
                return $response->idToken;
            }
        }
        return false;
    }

    static function getAccount($recall = false)
    {
        if($recall){
            self::$idToken = null;
        }

        if($response = self::send('accounts:lookup', [
            'idToken' => self::getIdToken(),
        ])){
            if(!empty($response->users) && !empty(!empty($response->users[0]))){
                return $response->users[0];
            }
        }

        if(!$recall){
            return self::getAccount(true);
        }

        return false;
    }

    static function emailVerificationSend($email = null, $recall = false)
    {
        if($recall){
            self::$idToken = null;
        }

        if($response = self::send('accounts:sendOobCode',[
            'requestType' => "VERIFY_EMAIL",
            'idToken' => self::getIdToken($email),
        ], ['X-Firebase-Locale: '.config('app.locale')])){
            //pusher_dump([$response, $email, !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null, !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null]);
            if(!empty($response->kind)){
                return true;
            }
        }

        if(empty($recall) && empty($email)){
            return self::emailVerificationSend(true);
        }

        return false;
    }

    static function emailVerificationCheck($oobCode)
    {
        if($response = self::send('accounts:update', [
            'oobCode' => $oobCode,
        ])){
            if(!empty($response->email)){
                return $response->email;
            }
        }
        return false;
    }

    static function deleteAccount($email = null, $recall = false)
    {
        if($recall){
            self::$idToken = null;
        }

        if($response = self::send('accounts:delete', [
            'idToken' => self::getIdToken($email, false),
        ])){
            if(!empty($response->kind)){
                return true;
            }
        }

        if(!$recall){
            return self::deleteAccount($email, true);
        }

        return false;
    }

    static function resetPasswordVerificationSend($email = null)
    {
        self::$email = !empty($email) ? $email : self::$email;
        self::getIdToken($email);

        if($response = self::send('accounts:sendOobCode', [
            'requestType' => 'PASSWORD_RESET',
            'email' => self::$email,
        ], ['X-Firebase-Locale: '.config('app.locale')])){
            //pusher_dump([$response, $email, !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null, !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null]);
            if(!empty($response->kind)){
                return true;
            }
        }
        //pusher_dump($response);
        return false;
    }

    static function resetPasswordVerificationCheck($oobCode)
    {
        if($response = self::send('accounts:resetPassword', [
            'oobCode' => $oobCode,
        ])){
            //pusher_dump([$response, $oobCode, !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null, !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null]);
            if(!empty($response->email)){
                return $response->email;
            }
        }
        return false;
    }
}