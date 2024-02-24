<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Console\Commands\Command;
use function Inspira\Utils\to_kebab;
use function Inspira\Utils\to_pascal;

class MakeView extends Command
{
	use FileCreator;

	protected string $description = "Make a view file or component class";

	protected array $requires = ['name'];

	public function run()
	{
		$name = to_kebab($this->input->getArgument('name'));
		$withComponent = $this->input->getArgument('c');

		if ($withComponent) {
			$className = ucwords(to_pascal($name), '/');

			$this->create('view.component', $className, app_path('Views'), ['VIEW_NAME' => 'protected ?string $view = ' . "'$name';"]);

			$view =  'components/' . $name;
		}

		$this->create('view', $view ?? $name, base_path('assets/views'));
	}
}
