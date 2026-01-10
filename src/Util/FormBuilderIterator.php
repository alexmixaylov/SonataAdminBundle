<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Util;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * @final since sonata-project/admin-bundle 3.52
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class FormBuilderIterator extends \RecursiveArrayIterator
{
    /**
     * @var \ReflectionProperty
     */
    protected static $reflection;

    protected FormBuilderInterface $formBuilder;

    /**
     * NEXT_MAJOR: Remove this property.
     *
     * @deprecated since sonata-project/sonata-admin-bundle 3.95
     *
     * @var mixed[]
     */
    protected array $keys = [];

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var \ArrayIterator<string|int, string|int>
     */
    protected $iterator;

    /**
     * NEXT_MAJOR: Change argument 2 to ?string $prefix = null.
     *
     * @param string|false $prefix
     */
    public function __construct(FormBuilderInterface $formBuilder, $prefix = null)
    {
        parent::__construct();
        $this->formBuilder = $formBuilder;

        // NEXT_MAJOR: Remove this block.
        if (null !== $prefix && !\is_string($prefix)) {
            @trigger_error(sprintf(
                'Passing other type than string or null as argument 2 for method %s() is deprecated since'
                .' sonata-project/admin-bundle 3.84. It will accept only string and null in version 4.0.',
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        // NEXT_MAJOR: Remove next line.
        $this->prefix = \is_string($prefix) ? $prefix : $formBuilder->getName();
        // NEXT_MAJOR: Uncomment next line.
        // $this->prefix = $prefix ?? $formBuilder->getName();
        $this->iterator = new \ArrayIterator(self::getKeys($formBuilder));
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    /**
     * @return string
     */
    public function key(): string
    {
        $name = $this->iterator->current();

        return sprintf('%s_%s', $this->prefix, $name);
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * @return FormBuilderInterface
     */
    public function current(): FormBuilderInterface
    {
        return $this->formBuilder->get($this->iterator->current());
    }

    /**
     * @return FormBuilderIterator
     */
    public function getChildren(): FormBuilderIterator
    {
        return new self($this->formBuilder->get($this->iterator->current()), $this->key());
    }

    public function hasChildren(): bool
    {
        return \count(self::getKeys($this->current())) > 0;
    }

    /**
     * @return array<string|int, string|int>
     */
    private static function getKeys(FormBuilderInterface $formBuilder): array
    {
        return array_keys($formBuilder->all());
    }
}
