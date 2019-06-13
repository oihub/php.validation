<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class NotEmpty extends Rule
{
    const EMPTY_VALUE = 'NotEmpty::EMPTY_VALUE';

    protected $templates = [
        self::EMPTY_VALUE => '{{ name }}不能为空.',
    ];

    protected $allowEmpty;
    protected $allowEmptyCallback;
    protected $input;

    public function __construct(bool $allowEmpty)
    {
        $this->allowEmpty = $allowEmpty;
    }

    public function validate($value): bool
    {
        if ($this->isEmpty($value)) {
            $this->break = true;
            return !$this->allowEmpty($this->input) ?
                $this->error(self::EMPTY_VALUE) : true;
        }
        return true;
    }

    protected function isEmpty($value)
    {
        if (is_string($value) && strlen($value) === 0) {
            return true;
        } elseif ($value === null) {
            return true;
        } elseif (is_array($value) && count($value) === 0) {
            return true;
        }
        return false;
    }

    public function setAllowEmpty($allowEmpty)
    {
        if (is_callable($allowEmpty)) {
            return $this->setAllowEmptyCallback($allowEmpty);
        }
        return $this->overwriteAllowEmpty($allowEmpty);
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'allowEmpty' => $this->allowEmpty,
            'callback' => $this->allowEmptyCallback
        ]);
    }

    protected function overwriteAllowEmpty($allowEmpty)
    {
        $this->allowEmpty = $allowEmpty;
        return $this;
    }

    protected function setAllowEmptyCallback(callable $allowEmptyCallback)
    {
        $this->allowEmptyCallback = $allowEmptyCallback;
        return $this;
    }

    protected function allowEmpty($input)
    {
        if (isset($this->allowEmptyCallback)) {
            $this->allowEmpty = call_user_func(
                $this->allowEmptyCallback,
                $input
            );
        }
        return $this->allowEmpty;
    }
}
