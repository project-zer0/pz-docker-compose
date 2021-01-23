<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class DownCommand extends BaseDockerComposeCommand
{
    protected function configure(): void
    {
        $this->setName('docker:compose:down')
            ->setAliases(['down'])
            ->setDescription('Stop and remove containers, networks, images, and volumes');

        parent::configure();
    }

    protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array {
        return ['down'];
    }
}
