<?php

namespace oihub\validation\rule;

class QQ extends Regex
{
    const INVALID_QQ = 'Alnum::INVALID_QQ';

    protected $templates = [
        self::INVALID_QQ => '{{ name }}格式错误.'
    ];

    public function __construct()
    {
        parent::__construct('/^[1-9]\d{4,12}$/');
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::INVALID_QQ);
    }
}
