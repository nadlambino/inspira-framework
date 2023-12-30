<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use Throwable;

class Make extends Command
{
	protected string $description = "Create file for the given options.";

	protected array $optionals = ['controller', 'model', 'command'];

	protected const DIR_MAP = [
		'controller' => './app/Controllers/',
		'model' => './app/Models/',
		'command' => './console/Commands/'
	];

	public function run(): never
	{
		$arguments = $this->input->getArguments();

		if (empty($arguments)) {
			$this->output->info('Please provide one of the following options: ' . trimplode(', ', $this->optionals));
		}

		foreach ($arguments as $key => $value) {
			if (empty($value) || !is_string($value)) {
				$this->output->error("$key name can't be empty");
			}

			// Ignore unknown arguments
			if (!in_array($key, $this->optionals)) {
				continue;
			}

			$this->create($key, $value);
		}

		exit(0);
	}

	private function create(string $type, string $filename)
	{
		try {
			$source = __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . ucwords($type);

			if (!file_exists($source)) {
				$this->output->error("Failed to create $type $filename", false);
				return;
			}

			$content = file_get_contents($source);
			$content = str_replace("CLASS_NAME", $filename, $content);

			$directory = self::DIR_MAP[$type];

			if (!file_exists($directory)) {
				mkdir($directory, 0777, true);
			}

			$target = $directory . $filename . '.php';

			if (file_exists($target)) {
				$filetype = ucwords($type);
				$this->output->info("$filetype $filename already exists.");
			}

			file_put_contents($target, $content);

			if (!file_exists($target)) {
				$this->output->error("Failed to create $type $target", false);
			}

			$this->output->success("$filename is successfully created", false);
		} catch (Throwable $exception) {
			$this->output->error($exception->getMessage(), false);
		}
	}
}
