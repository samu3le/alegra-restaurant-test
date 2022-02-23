<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\IsBase64;

/**
 * validate array, validate data content and type
 * @param \Illuminate\Http\Request  $request
 * @param Closure $next The next middleware.
 *
 * @return mixed Validate data , return array validated.
 */

class ListContent implements Rule, DataAwareRule
{
    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $type,
        protected $validations = null,
    )
    {
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            return true;
        }
        if (empty($value)) {
            return true;
        }

        $this->data['message_error'] = [];

        switch ($this->type) {
            case 'integer':
                foreach($value as $key => $v){
                    if (filter_var($v, FILTER_VALIDATE_INT)) {
                        unset($value[$key]);
                    }
                }
                $this->data['message_error'] = $value;
                break;
            case 'image':
                $array_of_errors = [];
                foreach($value as $key => $v){

                    $validator = Validator::make([
                        'v' => $v,
                    ], [
                        'v' => [new IsBase64(
                            types: ['png','jpg', 'jpeg', 'gif'],
                            size: 2048,
                        )],
                    ]);
                    if($validator->fails()){
                        unset($value[$key]);
                    }else{
                        array_push($array_of_errors, $key + 1);
                    }
                }
                $this->data['message_error'] = $array_of_errors;
                break;
            default:
                $value = [];
                break;
        }

        return count($value) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The :attribute must be list of ' . $this->type . ', the items with errors are: ' ;
        if(isset($this->data['message_error'])){
            $this->data['message_error'] = collect($this->data['message_error'])->map(function($item, $key){
                return json_encode($item, true) ? '[Object]' : $item;
            })->toArray();
            $message .= implode(',', $this->data['message_error']);
        }
        return $message;
    }
}
