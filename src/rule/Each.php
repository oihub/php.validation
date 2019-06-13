<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;
use oihub\validation\Validator;

class Each extends Rule
{
    const NOT_AN_ARRAY = 'Each::NOT_AN_ARRAY';

    protected $templates = [
        self::NOT_AN_ARRAY => '{{ name }}必须是数组类型.',
    ];

    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function validate($value): bool
    {
        if (!is_array($value)) {
            return $this->error(self::NOT_AN_ARRAY);
        }
        $result = true;
        foreach ($value as $index => $innerValue) {
            $result = $this->validateValue($index, $innerValue) && $result;
        }
        return $result;
    }

    protected function validateValue($index, $value)
    {
        $innerValidator = new Validator;
        call_user_func($this->callback, $innerValidator);
        $result = $innerValidator->validate($value);
        if (!$result->isValid()) {
            $this->handleError($index, $result);
            return false;
        }
        return true;
    }

    protected function handleError($index, $result)
    {
        foreach ($result->getFailures() as $failure) {
            $failure->overwriteKey(
                sprintf('%s.%s.%s', $this->key, $index, $failure->getKey())
            );
            $this->stack->append($failure);
        }
    }
}
