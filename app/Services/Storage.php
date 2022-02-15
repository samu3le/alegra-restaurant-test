<?php

namespace App\Services;

trait Storage
{
    private static $storage = 'storage/';

    public static function saveProductImage($base64){
        $name_file = time() . bin2hex(random_bytes(10));

        $path = self::$storage . 'products_images/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return self::save_base64_image($base64, $name_file ,$path);
    }

    public static function saveIngredientImage($base64){
        $name_file = time() . bin2hex(random_bytes(10));

        $path = self::$storage . 'ingredient_images/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return self::save_base64_image($base64, $name_file ,$path);
    }

    public static function deleteFile($path){
        if(file_exists($path)){
            unlink($path);
        }
    }

    private static function save_base64_image($base64, $name_file, $path ) {
        //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }
        //
        //data is like:    data:image/png;base64,asdfasdfasdf
        $splited = explode(',', substr( $base64 , 5 ) , 2);
        $mime=$splited[0];
        $data=$splited[1];

        $mime_split_without_base64=explode(';', $mime,2);
        $mime_split = explode('/', $mime_split_without_base64[0],2);
        if(count($mime_split)==2){
            $extension=$mime_split[1];
            //if($extension=='javascript')$extension='js';
            //if($extension=='text')$extension='txt';
            $output_file_with_extension = $name_file.'.'.$extension;
        }
        file_put_contents( $path . $output_file_with_extension, base64_decode($data) );
        return $path . $output_file_with_extension;
    }
}
