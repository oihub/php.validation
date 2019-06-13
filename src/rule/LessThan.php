<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class LessThan extends Rule
{
    const NOT_LESS_THAN = 'LessThan::NOT_LESS_THAN';

    protected $templates = [
        self::NOT_LESS_THAN => '{{ name }}必须小于{{ max }}.',
    ];

    protected $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function validate($value): bool
    {
        return !$this->notLessThan($value, self::NOT_LESS_THAN);
    }

    protected function notLessThan($value, string $error)
    {
        if ($value >= $this->max) {
            $this->error($error);
            return true;
        }
        return false;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'max' => $this->max,
        ]);
    }
}
