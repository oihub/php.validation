<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IsArray extends Rule
{
    const NOT_AN_ARRAY = 'IsArray::NOT_AN_ARRAY';

    protected $templates = [
        self::NOT_AN_ARRAY => '{{ name }}必须是数组类型.',
    ];

    public function validate($value): bool
    {
        if (is_array($value)) {
            return true;
        }
        return $this->error(self::NOT_AN_ARRAY);
    }
}
