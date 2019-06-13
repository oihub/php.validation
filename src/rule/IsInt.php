<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IsInt extends Rule
{
    const NOT_AN_INTEGER = 'IsInt::NOT_AN_INTEGER';
    const STRICT = true;
    const NOT_STRICT = false;

    protected $templates = [
        self::NOT_AN_INTEGER => '{{ name }}必须是整数类型.',
    ];

    private $strict;

    public function __construct(bool $strict = self::NOT_STRICT)
    {
        $this->strict = $strict;
    }

    public function validate($value): bool
    {
        if ($this->strict && is_int($value)) {
            return true;
        }
        if (
            !$this->strict &&
            false !== filter_var($value, FILTER_VALIDATE_INT)
        ) {
            return true;
        }
        return $this->error(self::NOT_AN_INTEGER);
    }
}
