<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Process;

use ProjectZer0\Pz\Process\Process;
use ProjectZer0\Pz\Process\ProcessInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class DockerComposeExecProcess implements ProcessInterface
{
    public const BINARY = '/usr/bin/docker-compose';

    public function __construct(
        protected array $configuration,
        protected string $env,
        protected string $serviceName,
        protected array $arguments = [],
        protected bool $detached = false,
        protected bool $interactive = true,
        protected array $envVariables = [],
        protected ?string $workDir = null,
        protected bool $exec = false,
    ) {
    }

    public function detach(): self
    {
        $this->detached = true;

        return $this;
    }

    public function nonInteractive(): self
    {
        $this->interactive = false;

        return $this;
    }

    public function addEnvVariable(string $value): self
    {
        $this->envVariables[] = $value;

        return $this;
    }

    public function setWorkDir(string $workDir): self
    {
        $this->workDir = $workDir;

        return $this;
    }

    public function replaceCurrentProcess(): self
    {
        $this->exec = true;

        return $this;
    }

    public function execute(): int
    {
        $process = $this->getProcess();

        return $process->execute();
    }

    public function getProcess(): Process
    {
        $args = [];

        $args[] = 'exec';

        if ($this->detached) {
            $args[] = '-d';
        }

        if (false === $this->interactive) {
            $args[] = '-T';
        }

        if (count($this->envVariables) > 0) {
            foreach ($this->envVariables as $envVariable) {
                $args[] = '-e';
                $args[] = $envVariable;
            }
        }

        if (null !== $this->workDir) {
            $args[] = '-w';
            $args[] = $this->workDir;
        }

        $args[] = $this->serviceName;

        $args = array_merge($args, $this->arguments);

        return new Process(
            static::BINARY,
            $args,
            environmentVariables: $this->getEnvVariables($this->env),
            replaceCurrentProcess: $this->exec
        );
    }

    protected function getEnvVariables(string $env): array
    {
        return array_merge(
            $_ENV,
            [
                'COMPOSE_PROJECT_NAME' => $this->configuration['docker_compose']['project_name'] ?? 'project-zer0',
                'COMPOSE_FILE'         => $this->configuration['docker_compose']['files'][$env] ?? './docker-compose.yaml',
            ]
        );
    }
}
