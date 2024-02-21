<?php

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\CommandRegistry as Registry;

class CommandRegistry extends Registry
{
	public function __construct()
	{
		$this->addCommand('app:serve', ApplicationServer::class);
		$this->addCommand('make:command', MakeCommand::class);
		$this->addCommand('make:controller', MakeController::class);
		$this->addCommand('make:model', MakeModel::class);
		$this->addCommand('make:view', MakeView::class);
		$this->addCommand('view:clear', ViewClear::class);
	}
}
