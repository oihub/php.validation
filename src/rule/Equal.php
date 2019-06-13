<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Equal extends Rule
{
    const NOT_EQUAL = 'Equal::NOT_EQUAL';

    protected $templates = [
        self::NOT_EQUAL => '{{ name }}必须等于"{{ testvalue }}".'
    ];

    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function validate($value): bool
    {
        if ($this->value === $value) {
            return true;
        }
        return $this->error(self::NOT_EQUAL);
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'testvalue' => $this->value
        ]);
    }
}
