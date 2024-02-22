<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Contracts\OutputInterface;
use Throwable;

/**
 * @property-read OutputInterface $output
 */
trait FileCreator
{
	protected function create(string $type, string $filename, string $directory): void
	{
		try {
			$filename = preg_replace('/[^a-zA-Z\d\-_.\/]/', '', $filename);
			$directory = trim($directory, DIRECTORY_SEPARATOR) . '/';
			$source = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $type;
			$type = $this->getType($type);

			if (!file_exists($source)) {
				$this->output->error("Failed to create $filename $type");
			}

			[$directory, $filename, $target, $namespace, $namespaceMarkerPrefix] = $this->extractParts($directory, $filename);
			$this->createDirectory($directory);

			if (file_exists($target)) {
				$this->output->info("$filename $type already exists.");
			}

			$this->saveContent($source, $filename, $target, $namespace, $namespaceMarkerPrefix);

			if (!file_exists($target)) {
				$this->output->error("Failed to create $target $type");
			}

			$this->output->success("$filename $type is successfully created", false);
		} catch (Throwable $exception) {
			$this->output->error($exception->getMessage());
		}
	}

	protected function getType(string $type): string
	{
		$parts = explode('.', $type);

		return end($parts);
	}

	protected function extractParts(string $directory, string $filename): array
	{
		$parts = explode('/', $filename);
		$filename = end($parts);

		$parent = array_slice($parts, 0, -1);
		$parent = implode(DIRECTORY_SEPARATOR, $parent);
		$directory .= $parent . DIRECTORY_SEPARATOR;

		$namespace = str_replace('/', '\\', $parent);
		$namespaceMarkerPrefix = empty($namespace) ? '\\' : '';

		$target = $directory . $filename . '.php';

		return [$directory, $filename, $target, $namespace, $namespaceMarkerPrefix];
	}

	protected function getContents(string $source, string $className, string $namespace, string $namespaceMarkerPrefix): string
	{
		$content = str_replace("{{ CLASS_NAME }}", $className, file_get_contents($source));

		return str_replace("$namespaceMarkerPrefix{{ NAMESPACE }}", $namespace, $content);
	}

	protected function createDirectory(string $directory): self
	{
		if (!file_exists($directory)) {
			mkdir($directory, 0777, true);
		}

		return $this;
	}

	protected function saveContent(string $source, string $filename, string $target, string $namespace, string $namespaceMarkerPrefix): void
	{
		$content = $this->getContents($source, $filename, $namespace, $namespaceMarkerPrefix);

		file_put_contents($target, $content);
	}
}
