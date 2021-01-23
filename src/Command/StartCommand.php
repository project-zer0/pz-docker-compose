<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class StartCommand extends BaseDockerComposeCommand
{
    protected function configure(): void
    {
        $this->setName('docker:compose:start')
            ->setAliases(['start'])
            ->setDescription('Start existing containers');

        parent::configure();
    }

    protected function getDockerComposeArguments(
        InputInterface $input,
        OutputInterface $output
    ): array {
        return ['start'];
    }
}
