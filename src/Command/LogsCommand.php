<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class LogsCommand extends BaseDockerComposeCommand
{
    protected function configure(): void
    {
        $this->setName('docker:compose:logs')
            ->setAliases(['logs'])
            ->setDescription('View output from containers')
            ->addOption(
                'follow',
                'f',
                InputOption::VALUE_NONE,
                'Follow log output'
            )
            ->addOption(
                'tail',
                null,
                InputOption::VALUE_REQUIRED,
                'Number of lines to show from the end of the logs for each container.',
                'all'
            )
            ->addOption(
                'timestamps',
                't',
                InputOption::VALUE_NONE,
                'Show timestamps.'
            )
            ->addArgument(
                'service',
                InputArgument::OPTIONAL,
                'Service name to fetch logs'
            );

        parent::configure();
    }

    protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array {
        $service = $input->getArgument('service');

        $args = ['logs'];

        if (true === $input->getOption('follow')) {
            $args[] = '-f';
        }

        if (true === $input->getOption('timestamps')) {
            $args[] = '-t';
        }

        $args[] = '--tail=' . $input->getOption('tail');

        if (null !== $service) {
            $args[] = $service;
        }

        return $args;
    }
}
