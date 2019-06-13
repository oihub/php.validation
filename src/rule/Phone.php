<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Phone extends Regex
{
    const INVALID_PHONE = 'Phone::INVALID_PHONE';

    protected $templates = [
        self::INVALID_PHONE => '{{ name }}格式错误.',
    ];

    public function __construct()
    {
        parent::__construct("/^1[3456789]{1}\d{9}$/");
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::INVALID_PHONE);
    }
}
