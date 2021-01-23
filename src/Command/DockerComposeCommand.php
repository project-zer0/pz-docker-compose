<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use ProjectZer0\Pz\Console\Command\ProcessCommand;
use ProjectZer0\Pz\Process\Process;
use ProjectZer0\Pz\Process\ProcessInterface;
use ProjectZer0\PzDockerCompose\Process\DockerComposeExecProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class DockerComposeCommand extends ProcessCommand
{
    protected function configure(): void
    {
        $this
            ->setName('docker:compose')
            ->setAliases(['dc', 'compose'])
            ->setDescription('Define and run multi-container applications with Docker')
            ->addOption(
                'env',
                null,
                InputOption::VALUE_REQUIRED,
                'Docker Compose environment file defined in ".pz.yaml"',
                'dev',
            );
    }

    public function getProcess(array $processArgs, InputInterface $input, OutputInterface $output): ProcessInterface
    {
        return new Process(
            DockerComposeExecProcess::BINARY,
            [],
            $this->getEnvVariables($input->getOption('env')),
            true,
        );
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
