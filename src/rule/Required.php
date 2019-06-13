<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;
use oihub\helper\ArrayHelper;

class Required extends Rule
{
    const NON_EXISTENT_KEY = 'Required::NON_EXISTENT_KEY';

    protected $templates = [
        self::NON_EXISTENT_KEY => '{{ name }}必填.'
    ];

    protected $required;
    protected $requiredCallback;
    protected $input;

    public function __construct(bool $required)
    {
        $this->required = $required;
    }

    public function validate($value): bool
    {
        return true;
    }

    public function isValid(string $key, array $input): bool
    {
        $this->required = $this->isRequired($input);
        if (!ArrayHelper::has($input, $key)) {
            $this->break = true;
            if ($this->required) {
                return $this->error(self::NON_EXISTENT_KEY);
            }
        }
        return $this->validate(ArrayHelper::get($input, $key));
    }

    public function setRequired($required)
    {
        if (is_callable($required)) {
            return $this->setRequiredCallback($required);
        }
        return $this->overwriteRequired((bool)$required);
    }

    protected function overwriteRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    protected function setRequiredCallback(callable $requiredCallback)
    {
        $this->requiredCallback = $requiredCallback;
        return $this;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'required' => $this->required,
            'callback' => $this->requiredCallback
        ]);
    }

    protected function isRequired(array $input)
    {
        if (isset($this->requiredCallback)) {
            $this->required = call_user_func_array(
                $this->requiredCallback,
                [$input]
            );
        }
        return $this->required;
    }
}
