<?php

namespace oihub\validation;

use oihub\base\BaseObject;

/**
 * Class Stack.
 * 
 * @author sean <maoxfjob@163.com>
 */
class Stack extends BaseObject
{
    /**
     * @var array 错误集合.
     */
    protected $failures = [];
    /**
     * @var array 错误信息集合.
     */
    protected $messages = [];
    /**
     * @var array 默认错误信息集合.
     */
    protected $defaultMessages = [];
    /**
     * @var array 重写错误信息集合.
     */
    protected $overwrites = [];

    /**
     * 追加错误信息.
     * @param Failure $failure 失败的验证.
     * @return void
     */
    public function append(Failure $failure): void
    {
        $key = $failure->getKey();
        $reason = $failure->getReason();
        if (isset($this->defaultMessages[$reason])) {
            $failure->overwriteMessageTemplate(
                $this->defaultMessages[$reason]
            );
        }
        if (isset($this->overwrites[$key][$reason])) {
            $failure->overwriteMessageTemplate(
                $this->overwrites[$key][$reason]
            );
        }
        $this->failures[] = $failure;
    }

    /**
     * 重写错误信息.
     * @param array $messages 错误信息集合.
     * @return self
     */
    public function overwriteMessages(array $messages): self
    {
        $this->overwrites = $messages;
        return $this;
    }

    /**
     * 重写默认错误信息.
     * @param array $messages 错误信息集合.
     * @return self
     */
    public function overwriteDefaultMessages(array $messages): self
    {
        $this->defaultMessages = $messages;
        return $this;
    }

    /**
     * 合并信息栈.
     * @param Stack $stack 信息栈.
     * @return void
     */
    public function merge(Stack $stack): void
    {
        $this->mergeDefaultMessages($stack);
        $this->mergeOverwrites($stack);
    }

    /**
     * 清空错误集合.
     * @return self
     */
    public function reset(): self
    {
        $this->failures = [];
        return $this;
    }

    /**
     * 得到错误集合.
     * @return array
     */
    public function getFailures(): array
    {
        return $this->failures;
    }

    /**
     * 合并默认信息栈.
     * @param Stack $stack 信息栈.
     * @return void
     */
    protected function mergeDefaultMessages(Stack $stack): void
    {
        foreach ($stack->defaultMessages as $key => $message) {
            if (!array_key_exists($key, $this->defaultMessages)) {
                $this->defaultMessages[$key] = $message;
            }
        }
    }

    /**
     * 合并重写信息栈.
     * @param Stack $stack 信息栈.
     * @return void
     */
    protected function mergeOverwrites(Stack $stack): void
    {
        foreach ($stack->overwrites as $key => $reasons) {
            foreach ($reasons as $reason => $message) {
                if (!$this->hasOverwrite($key, $reason)) {
                    $this->overwrites[$key][$reason] = $message;
                }
            }
        }
    }

    /**
     * 是否存在重写信息栈.
     * @param string $key 键名.
     * @param string $reason 原因.
     * @return bool
     */
    protected function hasOverwrite(string $key, string $reason): bool
    {
        return isset($this->overwrites[$key][$reason]);
    }
}
