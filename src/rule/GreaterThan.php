<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class GreaterThan extends Rule
{
    const NOT_GREATER_THAN = 'GreaterThan::NOT_GREATER_THAN';

    protected $templates = [
        self::NOT_GREATER_THAN => '{{ name }}必须大于{{ min }}.',
    ];

    protected $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function validate($value): bool
    {
        return !$this->notGreaterThan($value, self::NOT_GREATER_THAN);
    }

    protected function notGreaterThan($value, $error): bool
    {
        if ($value <= $this->min) {
            $this->error($error);
            return true;
        }
        return false;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'min' => $this->min,
        ]);
    }
}
