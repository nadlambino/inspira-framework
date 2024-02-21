<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class ApplicationServer extends Command
{
	protected string $description = 'Serve the application.';

	protected array $optionals = ['host', 'port'];

	public function run()
	{
		$port = $this->input->getArgument('port', '8000');
		$host = $this->input->getArgument('host', 'localhost');
		$address = "$host:$port";
		$root = 'public';
		$cmd = "php -S $address -t $root";

		$this->output->info("Booting application, please wait...", false);
		shell_exec($cmd);
		$this->output->error("Application failed to run!");
	}
}
