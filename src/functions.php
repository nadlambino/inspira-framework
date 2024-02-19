<?php

use Inspira\Container\Container;
use Inspira\Framework\Application;

if (!function_exists('container')) {
	function container(): Container
	{
		return Container::getInstance();
	}
}

if (!function_exists('app')) {
	function app(): Application
	{
		return Application::getInstance();
	}
}

if (!function_exists('base_url')) {
	function base_url(?string $path = null): string
	{
		$path = $path ? '/' . trim($path, '/') : null;

		return app()->getBaseUrl() . $path;
	}
}

if (!function_exists('base_path')) {
	function base_path(?string $path = null): string
	{
		$path = $path ? '/' . trim($path, '/') : null;

		return app()->getBasePath() . $path;
	}
}

if (!function_exists('app_path')) {
	function app_path(?string $path = null): string
	{
		$path = $path ? '/' . trim($path, '/') : null;

		return app()->getAppPath() . $path;
	}
}

if (!function_exists('vite')) {
	/**
	 * @throws Exception
	 */
	function vite(string $path)
	{
		$manifest = './manifest.json';
		if (!file_exists($manifest)) {
			throw new Exception("Vite manifest.json does not exists. Run npm run build.");
		}

		$resources = file_get_contents($manifest);
		$json = json_decode($resources, true);
		return isset($json[$path]) ? $json[$path]['file'] : '';
	}
}
