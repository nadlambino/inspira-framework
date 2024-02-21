<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use Inspira\Console\Contracts\InputInterface;
use Inspira\Console\Contracts\OutputInterface;
use Inspira\View\View;

class ViewClear extends Command
{
	protected string $description = "Clear view cache.";

	public function __construct(InputInterface $input, OutputInterface $output, protected View $view)
	{
		parent::__construct($input, $output);
	}

	public function run()
	{
		$cleared = $this->view->clearCache();

		if ($cleared === false) {
			$this->output->error('Failed to clear view caches.');
		}

		$this->output->success('Cleared view caches.');
	}
}
