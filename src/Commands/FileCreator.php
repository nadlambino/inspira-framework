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
			$filename = preg_replace('/[^a-zA-Z\d\-_.]/', '', $filename);
			$directory = trim($directory, DIRECTORY_SEPARATOR) . '/';
			$source = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $type;
			$explodedType = explode('.', $type);
			$filetype = end($explodedType);

			if (!file_exists($source)) {
				$this->output->error("Failed to create $filename $filetype");
			}

			$content = str_replace("CLASS_NAME", $filename, file_get_contents($source));

			if (!file_exists($directory)) {
				mkdir($directory, 0777, true);
			}

			$target = $directory . $filename . '.php';

			if (file_exists($target)) {
				$this->output->info("$filename $filetype already exists.");
			}

			file_put_contents($target, $content);

			if (!file_exists($target)) {
				$this->output->error("Failed to create $target $filetype");
			}

			$this->output->success("$filename $filetype is successfully created", false);
		} catch (Throwable $exception) {
			$this->output->error($exception->getMessage());
		}
	}
}
