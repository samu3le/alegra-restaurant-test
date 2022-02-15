<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\DB;

class ExistList implements Rule, DataAwareRule
{
    public $table = '';
    public $column = '';

    protected $data = [];

    public function __construct($table, $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            return true;
        }
        if (empty($value)) {
            return true;
        }

        foreach ($value as $item) {
            if (!filter_var($item, FILTER_VALIDATE_INT)) {
                return true;
            }
        }

        $ids = DB::table($this->table)->whereIn($this->column, $value)->pluck('id')->toArray();
        foreach($value as $key => $v){
            if(in_array($v, $ids)){
                unset($value[$key]);
            }
        }

        $this->data['value'] = $value;

        return $value === [];
    }

    public function message()
    {
        return 'The :attribute not found all items, the items are: ' . implode(', ', $this->data['value'] );
    }
}
