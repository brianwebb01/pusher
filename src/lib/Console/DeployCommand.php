<?php namespace Console;

use Configuration\ConfigLoader;
use Configuration\ConfigReader;
use Seld\JsonLint\JsonParser;
use Services\DeployService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @copyright   2014 Indatus
 */

class DeployCommand extends \Symfony\Component\Console\Command\Command
{

    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName('deploy')
            ->setDescription('Deploy your application')
            ->addArgument('environment', InputArgument::REQUIRED, 'Name of the environment to deloy')
            ->addOption('config', 'c', InputOption::VALUE_NONE, 'Specify a path to your configuration');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {
        $optionReader = new OptionReader($this->getOptions());
        $configReader = new ConfigReader(new ConfigLoader(new JsonParser()), $optionReader);
        $environment = $configReader->environment($this->input->getArgument('environment'));

        $service = new DeployService();
        $service->deploy($environment, $configReader);
    }
} 