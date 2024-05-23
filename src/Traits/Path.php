<?php

declare(strict_types=1);

namespace Inspira\Framework\Traits;

use Exception;
use Inspira\Http\Request;

trait Path
{
    /**
     * The base path where the application is installed
     *
     * @var string $basePath
     */
    private string $basePath = '..';

    /**
     * The path where the application files are located
     *
     * @var string $appPath
     */
    private string $appPath = '/app';

    /**
     * The path where the providers files are located
     *
     * @var string $providersPath
     */
    private string $providersPath = '/providers';

    /**
     * The path where the routes.php is located
     *
     * @var string $routesPath
     */
    private string $routesPath = '/app';

    /**
     * The path where the config files are located
     *
     * @var string $configsPath
     */
    private string $configsPath = '/configs';

    /**
     * The path where the database files are located
     *
     * @var string $databasePath
     */
    private string $databasePath = '/database';

    /**
     * The path where the views file are located
     *
     * @var string $viewsPath
     */
    private string $viewsPath = '/assets/views';

    /**
     * The path where the cache files are located
     *
     * @var string $cachePath
     */
    private string $cachePath = '/cache';

    /**
     * The path where the middleware files are located
     *
     * @var string $middlewaresPath
     */
    private string $middlewaresPath = '/app/Middlewares';


    /**
     * Get application base url
     *
     * @return string
     */
    public function getBaseUrl() : string
    {
        try {
            /** @var Request $request */
            $request = $this->make(Request::class);
            $url = (string) $request->getUri();
        } catch (Exception) {
            $url = $_SERVER['name'] ?? 'localhost';
        }

        $components = parse_url($url);

        $scheme = ($components['scheme'] ?? 'http') . '://';
        $host = $components['host'] ?? 'localhost';
        $port = isset($components['port']) && $components['port'] != '80' ? ':' . $components['port'] : '';

        return $scheme . $host . $port;
    }

    /**
     * Set the base path of the application
     *
     * @param string $path
     * @return static
     */
    public function setBasePath(string $path) : static
    {
        $this->basePath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the base path of the application
     *
     * @return string
     */
    public function getBasePath() : string
    {
        return realpath($this->basePath) ?: $this->basePath;
    }

    /**
     * Get the app path
     *
     * @return string
     */
    public function getAppPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->appPath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the provider's path
     *
     * @param string $path
     * @return static
     */
    public function setProvidersPath(string $path) : static
    {
        $this->providersPath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the provider's path
     *
     * @return string
     */
    public function getProvidersPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->providersPath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the routes
     *
     * @param string $path
     * @return $this
     */
    public function setRoutesPath(string $path) : static
    {
        $this->routesPath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the routes path
     *
     * @return string
     */
    public function getRoutesPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->routesPath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the configs path
     *
     * @param string $path
     * @return $this
     */
    public function setConfigsPath(string $path) : static
    {
        $this->configsPath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the configs path
     *
     * @return string
     */
    public function getConfigsPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->configsPath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the database path
     *
     * @param string $path
     * @return $this
     */
    public function setDatabasePath(string $path) : static
    {
        $this->databasePath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the database path
     *
     * @return string
     */
    public function getDatabasePath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->databasePath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the views path
     *
     * @param string $path
     * @return $this
     */
    public function setViewsPath(string $path) : static
    {
        $this->viewsPath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the views path
     *
     * @return string
     */
    public function getViewsPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->viewsPath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the cache path
     *
     * @param string $path
     * @return $this
     */
    public function setCachePath(string $path) : static
    {
        $this->cachePath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the cache path
     *
     * @return string
     */
    public function getCachePath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->cachePath;

        return realpath($path) ?: $path;
    }

    /**
     * Set the middlewares path
     *
     * @param string $path
     * @return $this
     */
    public function setMiddlewaresPath(string $path) : static
    {
        $this->middlewaresPath = trim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    /**
     * Get the middlewares path
     *
     * @return string
     */
    public function getMiddlewaresPath() : string
    {
        $path = $this->basePath . DIRECTORY_SEPARATOR . $this->middlewaresPath;

        return realpath($path) ?: $path;
    }

    /**
     * Validate the application paths
     *
     * @return void
     * @throws Exception
     */
    private function validatePaths() : void
    {
        $paths = [
            $this->getBasePath(),
            $this->getRoutesPath(),
            $this->getConfigsPath(),
            $this->getDatabasePath(),
            $this->getViewsPath(),
        ];

        foreach ($paths as $path) {
            if (! is_dir($path)) {
                throw new Exception("Directory `$path` does not exists. Configure the application directories properly.");
            }
        }
    }
}
