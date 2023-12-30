<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use Inspira\Console\Input;
use Inspira\Console\Output;
use Inspira\View\View as ViewAlias;

class View extends Command
{
	protected string $description = "All view available commands.";

	protected array $optionals = ['clear'];

	public function __construct(Input $input, Output $output, protected ViewAlias $view)
	{
		parent::__construct($input, $output);
	}

	public function __call(string $name, array $arguments)
	{
		if (!method_exists($this, $name)) {
			$this->output->error("Unknown view command $name");
		}
	}

	public function run()
	{
		$args = $this->input->getArguments();

		if (empty($args)) {
			$this->output->info("Please provide one of the following arguments: " .  implode(', ', $this->optionals));
		}

		foreach (array_keys($args) as $method) {
			$this->$method();
		}
	}

	protected function clear()
	{
		$cleared = $this->view->clearCache();

		if ($cleared === false) {
			$this->output->error('Failed to clear view caches.');
		}

		$this->output->success('Cleared view caches.', false);
	}
}
