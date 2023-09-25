<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Attribute;

use Attribute;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Argument\ArgumentInterface;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Exception\LogicException;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Reference;
use Staatic\Vendor\Symfony\Component\ExpressionLanguage\Expression;
#[Attribute(Attribute::TARGET_PARAMETER)]
class Autowire
{
    /**
     * @readonly
     * @var string|mixed[]|Expression|Reference|ArgumentInterface|null
     */
    public $value;
    /**
     * @readonly
     * @var bool|mixed[]
     */
    public $lazy;
    /**
     * @param string|mixed[]|ArgumentInterface $value
     * @param bool|string|mixed[] $lazy
     */
    public function __construct($value = null, string $service = null, string $expression = null, string $env = null, string $param = null, $lazy = \false)
    {
        if ($this->lazy = \is_string($lazy) ? [$lazy] : $lazy) {
            if (null !== ($expression ?? $env ?? $param)) {
                throw new LogicException('#[Autowire] attribute cannot be $lazy and use $expression, $env, or $param.');
            }
            if (null !== $value && null !== $service) {
                throw new LogicException('#[Autowire] attribute cannot declare $value and $service at the same time.');
            }
        } elseif (!(null !== $value xor null !== $service xor null !== $expression xor null !== $env xor null !== $param)) {
            throw new LogicException('#[Autowire] attribute must declare exactly one of $service, $expression, $env, $param or $value.');
        }
        if (\is_string($value) && strncmp($value, '@', strlen('@')) === 0) {
            switch (\true) {
                case strncmp($value, '@@', strlen('@@')) === 0:
                    $value = \substr($value, 1);
                    break;
                case strncmp($value, '@=', strlen('@=')) === 0:
                    $expression = \substr($value, 2);
                    break;
                default:
                    $service = \substr($value, 1);
                    break;
            }
        }
        switch (\true) {
            case null !== $service:
                $this->value = new Reference($service);
                break;
            case null !== $expression:
                if (!\class_exists(Expression::class)) {
                    throw new LogicException('Unable to use expressions as the Symfony ExpressionLanguage component is not installed. Try running "composer require symfony/expression-language".');
                }
                $this->value = new Expression($expression);
                break;
            case null !== $env:
                $this->value = "%env({$env})%";
                break;
            case null !== $param:
                $this->value = "%{$param}%";
                break;
            default:
                $this->value = $value;
                break;
        }
    }
}
