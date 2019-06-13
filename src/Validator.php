<?php

namespace oihub\validation;

use oihub\base\Component;

/**
 * Class Validator.
 * 
 * @author sean <maoxfjob@163.com>
 */
class Validator extends Component
{
    const DEFAULT_CONTEXT = 'default'; // 信息栈默认分组.

    /**
     * @var array 链式集合.
     */
    protected $chains = [self::DEFAULT_CONTEXT => []];
    /**
     * @var array 信息栈集合.
     */
    protected $stacks = [];
    /**
     * @var string 信息栈分组.
     */
    protected $context;

    /**
     * 构造函数.
     * @return void
     */
    public function __construct()
    {
        $this->context = self::DEFAULT_CONTEXT;
        $this->stacks[$this->context] = new Stack;
    }

    /**
     * 工厂模式.
     * @return self
     */
    public static function factory()
    {
        return new static();
    }

    /**
     * 创建一个必填的验证链.
     * @param string $key 键名.
     * @param string $name 别名.
     * @param bool $allowEmpty 是否允许为空.
     * @return Chain
     */
    public function required(
        string $key,
        string $name = '',
        bool $allowEmpty = false
    ): Chain {
        return $this->getChain($key, $name, true, $allowEmpty);
    }

    /**
     * 创建一个可选的验证链.
     * @param string $key 键名.
     * @param string $name 别名.
     * @param bool $allowEmpty 是否允许为空.
     * @return Chain
     */
    public function optional(
        string $key,
        string $name = '',
        bool $allowEmpty = true
    ): Chain {
        return $this->getChain($key, $name, false, $allowEmpty);
    }

    /**
     * 验证.
     * @param array $input 数据集合.
     * @param string $context 信息栈分组.
     * @return Result
     */
    public function validate(
        array $input,
        string $context = self::DEFAULT_CONTEXT
    ): Result {
        $isValid = true;
        $output = [];
        $stack = $this->mergeStack($context);
        foreach ($this->chains[$context] as $chain) {
            $isValid = $chain->validate($stack, $input, $output) && $isValid;
        }
        $result = new Result($isValid, $stack->getFailures(), $output);
        $stack->reset();
        return $result;
    }

    /**
     * 重写错误信息.
     * @param array $messages 错误信息集合.
     * @return self
     */
    public function overwriteMessages(array $messages): self
    {
        $this->getStack($this->context)->overwriteMessages($messages);
        return $this;
    }

    /**
     * 重写默认错误信息.
     * @param array $messages 错误信息集合.
     * @return self
     */
    public function overwriteDefaultMessages(array $messages): self
    {
        $this->getStack($this->context)->overwriteDefaultMessages($messages);
        return $this;
    }

    /**
     * 添加信息栈分组.
     * @param string $name 信息栈分组名称.
     * @param callable $callback 匿名函数.
     * @return void
     */
    public function context(string $name, callable $callback): void
    {
        $this->addStack($name);
        $this->context = $name;
        call_user_func($callback, $this);
        $this->context = self::DEFAULT_CONTEXT;
    }

    /**
     * 复制信息栈分组.
     * @param string $otherContext 信息栈分组.
     * @param callable $callback 匿名函数.
     * @return self
     */
    public function copyContext(
        string $otherContext,
        callable $callback = null
    ): self {
        $this->copyChains($otherContext, $callback);
        if ($otherContext !== self::DEFAULT_CONTEXT) {
            $this->getStack($this->context)->merge(
                $this->getStack($otherContext)
            );
        }
        return $this;
    }

    /**
     * 复制验证链.
     * @param string $otherContext 信息栈分组名称.
     * @param callable|null $callback 匿名函数.
     * @return void
     */
    protected function copyChains(
        string $otherContext,
        ? callable $callback
    ): void {
        if (isset($this->chains[$otherContext])) {
            $clonedChains = [];
            foreach ($this->chains[$otherContext] as $key => $chain) {
                $clonedChains[$key] = clone $chain;
            }
            $this->chains[$this->context] = $this->runChainCallback(
                $clonedChains,
                $callback
            );
        }
    }

    /**
     * 执行回调.
     * @param array $chains 验证链.
     * @param callable|null $callback 匿名函数.
     * @return array
     */
    protected function runChainCallback(
        array $chains,
        ? callable $callback
    ): array {
        $callback !== null and $callback($chains);
        return $chains;
    }

    /**
     * 添加信息栈分组.
     * @param string $context 信息栈分组.
     * @return void
     */
    protected function addStack(string $context): void
    {
        $this->stacks[$context] = new Stack;
    }

    /**
     * 得到信息栈内容.
     * @param string $context 信息栈分组.
     * @return Stack
     */
    protected function getStack(string $context): Stack
    {
        return $this->stacks[$context];
    }

    /**
     * 合并默认的信息栈.
     * @param string $context 信息栈分组.
     * @return Stack
     */
    protected function mergeStack(string $context): Stack
    {
        $stack = $this->getStack($context);
        if ($context === self::DEFAULT_CONTEXT) {
            $stack->merge($this->getStack(self::DEFAULT_CONTEXT));
        }
        return $stack;
    }

    /**
     * 得到验证链.
     * @param string $key 键名.
     * @param string $name 别名.
     * @param bool $required 是否必填.
     * @param bool $allowEmpty 是否允许为空.
     * @return Chain
     */
    protected function getChain(
        string $key,
        string $name,
        bool $required,
        bool $allowEmpty
    ): Chain {
        if (isset($this->chains[$this->context][$key])) {
            $chain = $this->chains[$this->context][$key];
            $chain->required($required);
            $chain->allowEmpty($allowEmpty);
            return $chain;
        }
        return $this->chains[$this->context][$key] = $this->buildChain(
            $key,
            $name,
            $required,
            $allowEmpty
        );
    }

    /**
     * 创建一个新的验证链.
     * @param string $key 键名.
     * @param string $name 别名.
     * @param bool $required 是否必填.
     * @param bool $allowEmpty 是否允许为空.
     * @return Chain
     */
    protected function buildChain(
        string $key,
        string $name,
        bool $required,
        bool $allowEmpty
    ): Chain {
        return new Chain($key, $name, $required, $allowEmpty);
    }
}
