<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage as StorageLaravel;

trait Storage
{
    private static $storage = 'storage/';

    public static function saveProductImage($base64){
        $name_file = time() . bin2hex(random_bytes(10));

        $image = self::StringToBase64($base64);

        $path = 'product_images/' . $name_file . '.' . $image['ext'];

        StorageLaravel::disk('public')->put($path, $image['file']);

        return $path;
    }

    public static function saveIngredientImage($base64){
        $name_file = time() . bin2hex(random_bytes(10));

        $base64 = str_replace(StorageLaravel::disk('public')->url(''), '', $base64);

        $image = self::StringToBase64($base64);

        $path = 'ingredient_images/' . $name_file . '.' . $image['ext'];

        StorageLaravel::disk('public')->put($path, $image['file']);

        return $path;
    }

    public static function deleteFile($path){
        $path = str_replace(StorageLaravel::disk('public')->url(''), '', $path);
        StorageLaravel::disk('public')->delete($path);
    }

    private static function StringToBase64($base64) {
        $splited = explode(',', substr( $base64 , 5 ) , 2);
        $mime=$splited[0];
        $data=$splited[1];

        $mime_split_without_base64=explode(';', $mime,2);
        $mime_split = explode('/', $mime_split_without_base64[0],2);
        $extension = '';
        if(count($mime_split) == 2){
            $extension = $mime_split[1];
            if( $extension == 'javascript' ) $extension = 'js';
            if( $extension == 'text' ) $extension = 'txt';
        }
        return [
            'ext' => $extension,
            'file' => base64_decode($data),
        ];
    }
}
