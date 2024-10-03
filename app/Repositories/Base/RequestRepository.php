<?php

namespace App\Repositories\Base;

class RequestRepository
{
    static function messages()
    {
        return [
            'required' => 'The :attribute is required.',
            'same'     => 'The :attribute and :other must match.',
            'min'      => 'The :attribute must be min :min.',
            'max'      => 'The :attribute must be max :max.',
            'size'     => 'The :attribute must be exactly :size.',
            'between'  => 'The :attribute must be between :min - :max.',
            'in'       => 'The :attribute must be one of the following types: :values.',
            'unique'   => 'The :attribute must be a unique, current :attribute exist.',
            'email'   => 'Please enter a valid email format and try again',
        ];
    }
}