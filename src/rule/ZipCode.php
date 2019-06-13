<?php

namespace oihub\validation\rule;

class ZipCode extends Regex
{
    const INVALID_ZIPCODE = 'Alnum::INVALID_ZIPCODE';

    protected $templates = [
        self::INVALID_ZIPCODE => '{{ name }}格式错误.'
    ];

    public function __construct()
    {
        parent::__construct('/^[1-9]\d{5}$/');
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::INVALID_ZIPCODE);
    }
}
