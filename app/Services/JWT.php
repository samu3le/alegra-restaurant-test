<?php

namespace App\Services;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

use App\Models\Session;

class JWT
{
    private static $key;

    private static $session = null;
    private static $errors = [];

    public function __construct(){
        self::$key = getenv("APP_KEY");
    }

    public static function TimeExpired($remember_me=false) : object
    {
        $expirationInSeconds = 60 * 60; // one hour
        
        if($remember_me){
            $expirationInSeconds = 60 * 60 * 24 * 7;
        }
        $dateTokenExpiration = time() + $expirationInSeconds;
        $dateTokenFormat = date('Y-m-d H:i:s', $dateTokenExpiration);

        return (object) [
            'dateTokenFormat' => $dateTokenFormat,
            'dateTokenExpiration' => $dateTokenExpiration,
            'expirationInSeconds' => $expirationInSeconds
        ];
    }

    public static function GenerateToken(
        string $uuid,
        int $user_id,
        $remember_me = false,
    ) : string
    {
        $timeExpired = self::TimeExpired($remember_me);

        $dateTokenExpiration = $timeExpired->dateTokenExpiration;
        $dateTokenFormat = $timeExpired->dateTokenFormat;

        $payload = [
            'uuid' => $uuid,
            'exp' => $dateTokenExpiration,
        ];

        $token = FirebaseJWT::encode($payload, self::$key, 'HS256');

        self::DestroyTokens($user_id);

        try {
            Session::create([
                'token' => $token,
                'user_id' => $user_id,
                'expired_at' => $dateTokenFormat,
            ]);
        } catch (\Throwable $th) {
            \Log::error([
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
        }

        return $token;
    }

    private static function ExistsToken(
        string $token,
    ) : bool
    {
        $session = Session::find($token);

        if(!$session){
            return false;
        }else if($session->isDeleted()){
            return false;
        }

        self::$session = $session;
        return true;
    }

    public static function VerifyToken(
        string $token,
    ) : void
    {
        if(!self::ExistsToken($token)){
            self::$errors = ['Token not found'];
            return;
        }

        $session = self::$session;

        if($session->expired_at < date('Y-m-d H:i:s')){
            self::DestroyTokens($session->user_id);
            self::$errors = ['Token expired'];
            return;
        }

        self::RefreshToken($session);
    }

    public static function RefreshToken(
        Session $session,
    ) : void
    {
        $timeExpired = self::TimeExpired();

        $dateTokenFormat = $timeExpired->dateTokenFormat;

        $session->update([
            'expired_at' => $dateTokenFormat,
        ]);
    }

    public static function DestroySession(
        Session $session,
    ) : void
    {
        $session->delete();
    }

    public static function DestroyTokens(
        int $user_id,
    ) : void
    {
        Session::where([
            'user_id' => $user_id,
        ])
        ->update([
            'delete_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function isValid(
    ) : bool
    {
        if(count(self::$errors) > 0){
            return false;
        }
        return true;
    }

    public static function errors(
    ) : array
    {
        return self::$errors;
    }

    public static function session(
    ) : Session
    {
        return self::$session;
    }
}