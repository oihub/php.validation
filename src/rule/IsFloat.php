<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IsFloat extends Rule
{
    const NOT_A_FLOAT = 'IsFloat::NOT_A_FLOAT';

    protected $templates = [
        self::NOT_A_FLOAT => '{{ name }}必须是浮点类型.',
    ];

    public function validate($value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return true;
        }
        return $this->error(self::NOT_A_FLOAT);
    }
}
