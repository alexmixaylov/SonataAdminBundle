<?php

namespace Symfony\Bridge\PhpUnit;

trait ExpectDeprecationTrait
{
    /**
     * @param string|null $message
     */
    public function expectDeprecation(string $message = null): void
    {
        // noop
    }

    /**
     * @param string $message
     */
    public function expectDeprecationMessage(string $message): void
    {
        // noop
    }

    /**
     * @param string $regex
     */
    public function expectDeprecationMessageMatches(string $regex): void
    {
        // noop
    }
}
