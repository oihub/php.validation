<?php

namespace oihub\validation\rule;

class Tel extends Regex
{
    const INVALID_TEL = 'Tel::INVALID_TEL';

    protected $templates = [
        self::INVALID_TEL => '{{ name }}格式错误.'
    ];

    public function __construct()
    {
        parent::__construct('/^([0-9]{3,4}-)?[0-9]{7,8}$/');
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::INVALID_TEL);
    }
}
