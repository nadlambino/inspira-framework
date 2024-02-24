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
	protected function create(string $type, string $filename, string $directory, array $replacements = []): void
	{
		try {
			$filename = preg_replace('/[^a-zA-Z\d\-_.\/]/', '', $filename);
			$directory = trim($directory, DIRECTORY_SEPARATOR) . '/';
			$source = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $type;
			$type = $this->getType($type);

			if (!file_exists($source)) {
				$this->output->error("Failed to create $filename $type");
			}

			[$directory, $filename, $target, $namespace] = $this->extractParts($directory, $filename);
			$this->createDirectory($directory);

			if (file_exists($target)) {
				$this->output->info("$filename $type already exists.");
			}

			$replacements['CLASS_NAME'] = $filename;
			$replacements['NAMESPACE'] = $namespace;

			$this->saveContent($source, $target, $replacements);

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
		$namespace = empty($namespace) ? '' : '\\' . $namespace;

		$target = $directory . $filename . '.php';

		return [$directory, $filename, $target, $namespace];
	}

	protected function createDirectory(string $directory): self
	{
		if (!file_exists($directory)) {
			mkdir($directory, 0777, true);
		}

		return $this;
	}

	protected function saveContent(string $source, string $target, array $replacements): void
	{
		$content = $this->getContents($source, $replacements);

		file_put_contents($target, $content);
	}

	protected function getContents(string $source, array $replacements): string
	{
		$contents = file_get_contents($source);

		foreach ($replacements as $key => $value) {
			$contents = str_replace("{{ $key }}", $value, $contents);
		}

		return $contents;
	}
}
