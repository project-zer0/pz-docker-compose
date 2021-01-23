<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class StopCommand extends BaseDockerComposeCommand
{
    protected function configure(): void
    {
        $this->setName('docker:compose:stop')
            ->setAliases(['stop'])
            ->setDescription('Stop running containers without removing them');

        parent::configure();
    }

    protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array {
        return ['stop'];
    }
}
