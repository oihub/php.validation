<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Numeric extends Rule
{
    const NOT_NUMERIC = 'Numeric::NOT_NUMERIC';

    protected $templates = [
        self::NOT_NUMERIC => '{{ name }}必须是数字或数字字符串.',
    ];

    public function validate($value): bool
    {
        if (is_numeric($value)) {
            return true;
        }
        return $this->error(self::NOT_NUMERIC);
    }
}
