<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Str;

use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Validator;

use Illuminate\Validation;
use Illuminate\Filesystem;
use Illuminate\Translation;

class IsBase64 implements Rule, DataAwareRule
{
    public $errors = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected $types = [],
        protected $size = 2048
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
        if(!is_string($value)){
            return false;
        }

        $type_valid = false;
        foreach($this->types as $type){
            if(Str::startsWith($value, "data:image/{$type};base64,")){
                $type_valid = true;
                break;
            }
        }
        if(!$type_valid){
            return false;
        }

        $filesystem = new Filesystem\Filesystem();
        $fileLoader = new Translation\FileLoader($filesystem, '');
        $translator = new Translation\Translator($fileLoader, 'en_US');
        $factory = new Validation\Factory($translator);

        $file = $this->createTemporaryFile($value);

        $validator = Validator::make(
            ['file' => $file ],
            ['file' => ['image', 'max:'.$this->size]],
        );

        if($validator->fails()){
            fclose($this->file);
            $this->errors = (array) $validator->errors->all();
            return false;
        }

        fclose($this->file);
        return true;
    }
    protected function createTemporaryFile($data)
    {
        $this->file = tmpfile();
        fwrite($this->file, base64_decode(Str::after($data, 'base64,')));
        return new UploadedFile(
            stream_get_meta_data($this->file)['uri'], 'image',
            'text/plain', null, true, true
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid image. ' . implode(', ', $this->errors );
    }
}
