<?php

namespace Inspira\Framework\Traits;

use Inspira\Framework\Application;
use Inspira\Framework\Providers\ApplicationProvider;
use Inspira\Framework\Providers\DatabaseProvider;
use Inspira\Framework\Providers\HttpProvider;
use function Inspira\Utils\get_files_from;

trait Provider
{
    /**
     * The namespace where the providers are located
     *
     * @var string $providersNamespace
     */
    private string $providersNamespace = 'Providers\\';

    /**
     * The list of built-in providers
     *
     * @var array $providers
     */
    private array $providers = [
        ApplicationProvider::class,
        HttpProvider::class,
        DatabaseProvider::class,
    ];

    /**
     * Get the list of providers
     *
     * @return array
     */
    public function getProviders() : array
    {
        return $this->providers;
    }

    /**
     * Autoload providers from the given namespace
     *
     * @param string $namespace
     * @return Application
     */
    public function autoloadProvidersFrom(string $namespace) : static
    {
        $this->providersNamespace = trim($namespace, '\\') . '\\';

        return $this;
    }

    /**
     * Get the list of providers created by the user
     *
     * @return array
     */
    protected function getUserProviders() : array
    {
        $this->loader?->addPsr4($this->providersNamespace, $this->getProvidersPath());

        $files = get_files_from($this->getProvidersPath(), 'php');

        return array_filter(array_map(function ($file) {
            $class = ucfirst(trim(str_replace([$this->getBasePath(), '.php', '/'], ['', '', '\\'], $file), '\\'));

            return class_exists($class) ? $class : null;
        }, $files));
    }

    /**
     * Add providers to the list of providers
     *
     * @param array $providers
     * @return Application
     */
    public function addProviders(array $providers) : static
    {
        $this->providers = array_merge($this->getProviders(), $providers);

        return $this;
    }
}