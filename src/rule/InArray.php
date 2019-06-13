<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class InArray extends Rule
{
    const NOT_IN_ARRAY = 'InArray::NOT_IN_ARRAY';
    const STRICT = true;
    const NOT_STRICT = false;

    protected $templates = [
        self::NOT_IN_ARRAY => '{{ name }}必须是可选值.',
    ];

    protected $array = [];
    protected $strict;

    public function __construct(array $array, bool $strict = self::STRICT)
    {
        $this->array = $array;
        $this->strict = $strict;
    }

    public function validate($value): bool
    {
        if (in_array($value, $this->array, $this->strict)) {
            return true;
        }
        return $this->error(self::NOT_IN_ARRAY);
    }

    protected function getParams(): array
    {
        $quote = function ($value) {
            return '"' . $value . '"';
        };
        return array_merge(parent::getParams(), [
            'values' => implode(', ', array_map($quote, $this->array))
        ]);
    }
}
