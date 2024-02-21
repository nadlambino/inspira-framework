<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use Inspira\Console\Contracts\InputInterface;
use Inspira\Console\Contracts\OutputInterface;
use Inspira\Framework\Application;
use Throwable;

class Make extends Command
{
	protected string $description = "Create file for the given options.";

	protected array $optionals = ['controller', 'model', 'command', 'view'];

	protected array $dirMap = [];

	public function __construct(InputInterface $input, OutputInterface $output, Application $application)
	{
		$this->input = $input;
		$this->output = $output;
		$this->dirMap = [
			'controller' => app_path('Controllers'),
			'model' => app_path('Models'),
			'command' => app_path('Commands'),
			'view' => $application->getViewsPath()
		];

		parent::__construct($input, $output);
	}

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
			$source = __DIR__ . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . ucwords($type);

			if (!file_exists($source)) {
				$this->output->error("Failed to create $type $filename", false);
				return;
			}

			$content = file_get_contents($source);
			$content = str_replace("CLASS_NAME", $filename, $content);

			$directory = $this->dirMap[$type] ?? null;

			if (!$directory) {
				$this->output->error("Failed to create $type $filename", false);
				return;
			}

			$directory .= '/';

			if (!file_exists($directory)) {
				mkdir($directory, 0777, true);
			}

			$target = $directory . $filename . '.php';

			if (file_exists($target)) {
				$this->output->info("$filename $type already exists.");
			}

			file_put_contents($target, $content);

			if (!file_exists($target)) {
				$this->output->error("Failed to create $type $target", false);
			}

			$this->output->success("$filename $type is successfully created", false);
		} catch (Throwable $exception) {
			$this->output->error($exception->getMessage(), false);
		}
	}
}
