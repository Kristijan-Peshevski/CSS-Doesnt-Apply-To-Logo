<?php

namespace Staatic\Vendor\Symfony\Component\DependencyInjection\Argument;

use Closure;
use Staatic\Vendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Definition;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Staatic\Vendor\Symfony\Component\DependencyInjection\Reference;
use Staatic\Vendor\Symfony\Component\VarExporter\ProxyHelper;
class LazyClosure
{
    /**
     * @var \Closure
     */
    private $initializer;
    /**
     * @readonly
     * @var object
     */
    public $service;
    public function __construct(Closure $initializer)
    {
        $this->initializer = $initializer;
        unset($this->service);
    }
    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get($name)
    {
        if ('service' !== $name) {
            throw new InvalidArgumentException(\sprintf('Cannot read property "%s" from a lazy closure.', $name));
        }
        if (isset($this->initializer)) {
            $this->service = ($this->initializer)();
            unset($this->initializer);
        }
        return $this->service;
    }
    /**
     * @param string $initializer
     * @param mixed[] $callable
     * @param Definition $definition
     * @param ContainerBuilder $container
     * @param string|null $id
     */
    public static function getCode($initializer, $callable, $definition, $container, $id) : string
    {
        $method = $callable[1];
        $asClosure = 'Closure' === ($definition->getClass() ?: 'Closure');
        if ($asClosure) {
            $class = ($callable[0] instanceof Reference ? $container->findDefinition($callable[0]) : $callable[0])->getClass();
        } else {
            $class = $definition->getClass();
        }
        $r = $container->getReflectionClass($class);
        if (null !== $id) {
            $id = \sprintf(' for service "%s"', $id);
        }
        if (!$asClosure) {
            $id = \str_replace('%', '%%', (string) $id);
            if (!$r || !$r->isInterface()) {
                throw new RuntimeException(\sprintf("Cannot create adapter{$id} because \"%s\" is not an interface.", $class));
            }
            if (1 !== \count($method = $r->getMethods())) {
                throw new RuntimeException(\sprintf("Cannot create adapter{$id} because interface \"%s\" doesn't have exactly one method.", $class));
            }
            $method = $method[0]->name;
        } elseif (!$r || !$r->hasMethod($method)) {
            throw new RuntimeException("Cannot create lazy closure{$id} because its corresponding callable is invalid.");
        }
        $code = ProxyHelper::exportSignature($r->getMethod($method), \true, $args);
        if ($asClosure) {
            $code = ' { ' . \preg_replace('/: static$/', ': \\' . $r->name, $code);
        } else {
            $code = ' implements \\' . $r->name . ' { ' . $code;
        }
        $code = 'new class(' . $initializer . ') extends \\' . self::class . $code . ' { return $this->service->' . $callable[1] . '(' . $args . '); } ' . '}';
        return $asClosure ? '(' . $code . ')->' . $method . '(...)' : $code;
    }
}
