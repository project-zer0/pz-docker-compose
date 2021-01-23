<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use InvalidArgumentException;
use ProjectZer0\Pz\Process\Process;
use ProjectZer0\PzDockerCompose\Process\DockerComposeExecProcess;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class UpCommand extends BaseDockerComposeCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this->setName('docker:compose:up')
            ->setAliases(['up'])
            ->setDescription('Builds, (re)creates, starts, and attaches to containers for application')
            ->addOption(
                'down',
                'd',
                InputOption::VALUE_NONE,
                'Run docker-compose down before'
            )
            ->addOption(
                'no-detach',
                null,
                InputOption::VALUE_NONE,
                'Disables Detached mode: Run containers in the foreground'
            )
            ->addOption(
                'pull',
                null,
                InputOption::VALUE_NONE,
                'Pull without printing progress information'
            )
            ->addOption(
                'force-recreate',
                null,
                InputOption::VALUE_NONE,
                'Recreate containers even if their configuration and image haven\'t changed.'
            )
            ->addOption(
                'build',
                'b',
                InputOption::VALUE_NONE,
                'Build images before starting containers.'
            );
    }

    protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array {
        $down = $this->getConfiguration()['docker']['compose']['operations']['up']['down'] ?? false;
        if (true === $input->getOption('down')) {
            $down = true;
        }

        $detach = $this->getConfiguration()['docker']['compose']['operations']['up']['detach'] ?? true;
        if (true === $input->getOption('no-detach')) {
            $detach = false;
        }

        $pull = $this->getConfiguration()['docker']['compose']['operations']['up']['pull'] ?? true;
        if (true === $input->getOption('pull')) {
            $pull = true;
        }

        $forceRecreate = $this->getConfiguration()['docker']['compose']['operations']['up']['force-recreate'] ?? false;
        if (true === $input->getOption('force-recreate')) {
            $forceRecreate = true;
        }

        $build = $this->getConfiguration()['docker']['compose']['operations']['up']['build'] ?? true;
        if (true === $input->getOption('build')) {
            $build = true;
        }

        if ($down) {
            $downProcess = new Process(
                DockerComposeExecProcess::BINARY,
                ['down'],
                $this->getEnvVariables($input->getOption('env')),
                false,
            );

            $exitCode = $downProcess->execute();

            if (0 !== $exitCode) {
                throw new InvalidArgumentException('docker-compose -f ... down failed');
            }
        }

        $args = ['up'];
        if ($detach) {
            $args[] = '-d';
        }

        if ($pull) {
            $args[] = '--quiet-pull';
        }

        if ($forceRecreate) {
            $args[] = '--force-recreate';
        }

        if ($build) {
            $args[] = '--build';
        }

        return $args;
    }
}
