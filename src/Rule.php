<?php

namespace oihub\validation;

use oihub\base\BaseObject;
use oihub\helper\ArrayHelper;

/**
 * Class Rule.
 * 
 * @author sean <maoxfjob@163.com>
 */
abstract class Rule extends BaseObject
{
    /**
     * @var string 键名.
     */
    protected $key;
    /**
     * @var string 别名.
     */
    protected $name;
    /**
     * @var array 数据集合.
     */
    protected $input;
    /**
     * @var Stack 信息栈.
     */
    protected $stack;
    /**
     * @var array 信息模板集合.
     */
    protected $templates = [];
    /**
     * @var bool 打断.
     */
    protected $break = false;
    /**
     * @var bool 验证失败是否打断.
     */
    protected $breakOnError = true;

    /**
     * 验证.
     * @param mixed $value 数据.
     * @return bool
     */
    abstract public function validate($value): bool;

    /**
     * 验证结果.
     * @param string $key 键名.
     * @param array $input 数据集合.
     * @return bool
     */
    public function isValid(string $key, array $input): bool
    {
        $this->input = $input;
        return $this->validate(ArrayHelper::get($input, $key));
    }

    /**
     * 设置验证规则的默认参数.
     * @param string $key 键名.
     * @param string $name 别名.
     * @return self
     */
    public function setParams(string $key, string $name): self
    {
        $this->key = $key;
        $this->name = $name;
        return $this;
    }

    /**
     * 设置信息栈.
     * @param Stack $stack
     * @return self
     */
    public function setStack(Stack $stack): self
    {
        $this->stack = $stack;
        return $this;
    }

    /**
     * 是否打断.
     * @return bool
     */
    public function break(): bool
    {
        return $this->break;
    }

    /**
     * 验证失败是否打断.
     * @return bool
     */
    public function breakOnError(): bool
    {
        return $this->breakOnError;
    }

    /**
     * 错误信息栈.
     * @param string $reason 原因.
     * @return bool
     */
    protected function error(string $reason): bool
    {
        $this->stack->append(new Failure(
            $this->key,
            $reason,
            $this->getMessage($reason),
            $this->getParams()
        ));
        return false;
    }

    /**
     * 得到参数.
     * @return array
     */
    protected function getParams(): array
    {
        return [
            'key' => $this->key,
            'name' => empty($this->name) ? $this->key : $this->name,
            // str_replace('_', ' ', $this->key) :
        ];
    }

    /**
     * 得到错误信息.
     * @param string $reason 原因.
     * @return string
     */
    protected function getMessage(string $reason): string
    {
        if (array_key_exists($reason, $this->templates)) {
            $template = $this->templates[$reason];
        }
        return $template ?? '';
    }
}
