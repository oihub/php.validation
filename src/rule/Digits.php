<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Digits extends Rule
{
    const NOT_DIGITS = 'Digits::NOT_DIGITS';

    protected $templates = [
        self::NOT_DIGITS => '{{ name }}必须是数字字符串.',
    ];

    public function validate($value): bool
    {
        if (ctype_digit((string)$value)) {
            return true;
        }
        return $this->error(self::NOT_DIGITS);
    }
}
