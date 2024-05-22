<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;

class ApplicationServer extends Command
{
	protected string $description = 'Serve the application';

	protected array $options = ['host', 'port'];

	public function run() : void
    {
		$port = $this->input->getOption('port', '8000');
		$host = $this->input->getOption('host', 'localhost');
		$address = "$host:$port";
		$root = 'public';
		$cmd = "php -S $address -t $root";

		$this->output->info("Booting application, please wait...", false);
		shell_exec($cmd);
		$this->output->error("Application failed to run!");
	}
}
