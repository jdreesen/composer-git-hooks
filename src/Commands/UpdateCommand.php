<?php

namespace BrainMaestro\GitHooks\Commands;

use BrainMaestro\GitHooks\Hook;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    private $hooks;

    public function __construct($hooks)
    {
        $this->hooks = $hooks;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Update git hooks specified in the composer config')
            ->setHelp('This command allows you to update git hooks')
            ->addOption('git-dir', 'g', InputOption::VALUE_REQUIRED, 'Path to git directory', '.git')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gitDir = $input->getOption('git-dir');

        foreach ($this->hooks as $hook => $script) {
            $filename = "{$gitDir}/hooks/{$hook}";

            $operation = file_exists($filename) ? 'Updated' : 'Added';
            file_put_contents($filename, $script);
            chmod($filename, 0755);
            $output->writeln("{$operation} '{$hook}' hook");
        }
    }
}
