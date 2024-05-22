<?php

declare(strict_types=1);

namespace Inspira\Framework\Commands;

use Inspira\Config\Config;
use Inspira\Console\Commands\Command;
use Inspira\Console\Contracts\InputInterface;
use Inspira\Console\Contracts\OutputInterface;
use function Inspira\Utils\to_kebab;
use function Inspira\Utils\to_pascal;

class MakeView extends Command
{
    use FileCreator;

    protected string $description = "Make a view file";

    protected ?string $argument = 'name';

    protected array $options = ['component'];

    public function __construct(InputInterface $input, OutputInterface $output, protected Config $config)
    {
        parent::__construct($input, $output);
    }

    public function run() : void
    {
        $name = to_kebab($this->input->getArgument());
        $withComponent = $this->input->getOption('component');
        $viewName = $withComponent ? 'components/' . $name : $name;

        $created = $this->create('view', $viewName, $this->config->get('view.views_path', base_path('assets/views')));

        if ($created && $withComponent) {
            $className = ucwords(to_pascal($name), '/');

            $this->create('view.component', $className, app_path('Views'), ['VIEW_NAME' => 'protected ?string $view = ' . "'$name';"]);
        }
    }
}
