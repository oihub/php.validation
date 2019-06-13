<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Callback extends Rule
{
    const INVALID_CALLBACK = 'Callback::INVALID_CALLBACK';

    protected $templates = [
        self::INVALID_CALLBACK => '{{ name }}无效的回调函数.',
    ];

    protected $callback;
    protected $input;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function validate($value): bool
    {
        try {
            $result = call_user_func(
                $this->callback,
                $value,
                $this->values
            );
            if ($result === true) {
                return true;
            }
            return $this->error(self::INVALID_CALLBACK);
        } catch (\Exception $e) {
            return $this->error(self::INVALID_CALLBACK);
        }
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'callback' => $this->callback,
        ]);
    }
}
