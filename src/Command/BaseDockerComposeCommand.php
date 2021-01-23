<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use ProjectZer0\Pz\Console\Command\PzCommand;
use ProjectZer0\Pz\Process\Process;
use ProjectZer0\PzDockerCompose\Process\DockerComposeExecProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
abstract class BaseDockerComposeCommand extends PzCommand
{
    protected function configure(): void
    {
        $this->addOption(
            'env',
            null,
            InputOption::VALUE_REQUIRED,
            'Docker Compose environment file defined in ".pz.yaml"',
            'dev',
        );
    }

    abstract protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = new Process(
            DockerComposeExecProcess::BINARY,
            $this->getDockerComposeArguments($input, $output),
            $this->getEnvVariables($input->getOption('env')),
            true,
        );

        return $process->execute();
    }

    protected function getEnvVariables(string $env): array
    {
        return array_merge(
            $_ENV,
            [
                'COMPOSE_PROJECT_NAME' => $this->getConfiguration()['docker_compose']['project_name'] ?? 'project-zer0',
                'COMPOSE_FILE'         => $this->getConfiguration(
                    )['docker_compose']['files'][$env] ?? './docker-compose.yaml',
            ]
        );
    }
}
