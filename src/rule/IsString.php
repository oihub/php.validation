<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IsString extends Rule
{
    const NOT_A_STRING = 'IsString::NOT_A_STRING';

    protected $templates = [
        self::NOT_A_STRING => '{{ name }}必须是字符串类型.',
    ];

    public function validate($value): bool
    {
        if (is_string($value)) {
            return true;
        }
        return $this->error(self::NOT_A_STRING);
    }
}
