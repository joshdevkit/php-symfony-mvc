<?php

namespace Core;

use Core\Validation\ValidatesWhenResolved;
use Core\Validation\Validator;

abstract class FormRequest extends Request implements ValidatesWhenResolved
{
    protected $validator;

    public function __construct(array $getParams, array $postParams, array $cookies, array $files, array $serverParams)
    {
        parent::__construct($getParams, $postParams, $cookies, $files, $serverParams);
        $this->validator = new Validator();
    }

    public function validate()
    {
        $rules = $this->rules();
        $this->validator->validate($this->all(), $rules);
        return $this->validator->validated();
    }

    abstract public function rules(): array;
}
