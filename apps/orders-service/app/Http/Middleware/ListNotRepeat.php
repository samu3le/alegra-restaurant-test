<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Validation\Rule;

/**
 * validate array, find data not repeat at array to validate
 * @param \Illuminate\Http\Request  $request
 * @param Closure $next The next middleware.
 *
 * @return mixed Validate data , return array validated.
 */

class ListNotRepeat implements Rule
{
    protected $data = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
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
        return count($value) === count(array_unique($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute should not be repeated.';
    }
}
