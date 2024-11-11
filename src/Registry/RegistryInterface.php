<?php
declare(strict_types=1);

namespace Ubeliakou\OneTimeOperationSdk\Registry;

use Ubeliakou\OneTimeOperationSdk\Registry\Exception\RegistryException;

interface RegistryInterface
{
    /**
     * @throws RegistryException
     */
    public function ensureTableExists(): void;

    /**
     * @return string[]
     * @throws RegistryException
     */
    public function getExecuted(): array;

    /**
     * @param string $timestamp
     * @throws RegistryException
     */
    public function markAsExecuted(string $timestamp): void;
}