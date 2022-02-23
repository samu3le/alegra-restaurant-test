<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\DB;

class Validator
{
    public static function make($data, $rules, $messages = [], $customAttributes = [])
    {

        ValidatorFacade::extend('iunique', function ($attribute, $value, $parameters, $validator) {
            $query = DB::table($parameters[0]);
    
            if (isset($parameters[2])) {
                if (isset($parameters[3])) {
                    $idCol = $parameters[3];
                } else {
                    $idCol = 'id';
                }
    
                $query->where($idCol, '!=', $parameters[2]);
            }

            if (!isset($parameters[1])) {
                $column = $attribute;
            }else{
                $column = $parameters[1];
            }

            $query->whereRaw("lower({$column}) = lower(?)", [$value]);
            
            $count = $query->count();

            $customMessage = "The $column has already been taken.";
            $validator->addReplacer('iunique', 
                function($message, $attribute, $rule, $parameters) use ($customMessage) {
                    return \str_replace(':custom_message', $customMessage, $message);
                },
            );

            return ! $count;
        }, ':custom_message');

        return ValidatorFacade::make($data, $rules, $messages, $customAttributes);
    }
}