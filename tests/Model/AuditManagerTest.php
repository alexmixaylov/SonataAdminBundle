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

namespace Sonata\AdminBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Model\AuditManager;
use Sonata\AdminBundle\Model\AuditReaderInterface;
use Symfony\Bridge\PhpUnit\ExpectDeprecationTrait;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
final class AuditManagerTest extends TestCase
{
    // NEXT_MAJOR: Remove next line.
    use ExpectDeprecationTrait;

    public function testGetReader(): void
    {
        $container = new Container();

        $fooReader = $this->createStub(AuditReaderInterface::class);
        $barReader = $this->createStub(AuditReaderInterface::class);

        $container->set('foo_reader', $fooReader);
        $container->set('bar_reader', $barReader);

        $auditManager = new AuditManager($container);

        static::assertFalse($auditManager->hasReader('Foo\Foo1'));

        $auditManager->setReader('foo_reader', ['Foo\Foo1', 'Foo\Foo2']);

        static::assertTrue($auditManager->hasReader('Foo\Foo1'));
        static::assertSame($fooReader, $auditManager->getReader('Foo\Foo1'));
    }

    public function testGetReaderWithException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The class "Foo\Foo" does not have any reader manager');

        $auditManager = new AuditManager(new Container());

        $auditManager->getReader('Foo\Foo');
    }
}
