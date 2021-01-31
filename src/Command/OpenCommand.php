<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose\Command;

use ProjectZer0\Pz\Console\Command\PzCommand;
use ProjectZer0\Pz\ProjectZer0Toolkit;
use ProjectZer0\Pz\RPC\Command\OpenURLRpcCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OpenCommand extends PzCommand
{
    public function __construct(ProjectZer0Toolkit $toolkit)
    {
        $this->toolkit = $toolkit;

        parent::__construct();
    }

    /**
     * @psalm-suppress InvalidConsoleArgumentValue
     */
    protected function configure(): void
    {
        $defaultService = $this->getConfiguration()['docker_compose']['open_url_default'] ?? null;

        $this->setName('docker:compose:open')
            ->setAliases(['open', 'o'])
            ->setDescription('Open URL in browser from defined services in .pz.yaml file')
            ->addArgument(
                'service',
                null === $defaultService ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
                'A service name from .pz.yaml file',
                $defaultService,
            );
    }

    /**
     * @psalm-suppress PossiblyInvalidOperand
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serviceName = $input->getArgument('service');

        $services = $this->getConfiguration()['docker_compose']['open_urls'] ?? [];

        $io = new SymfonyStyle($input, $output);

        if (false === isset($services[$serviceName])) {
            $io->error('Service "' . $serviceName . '" not found in ".pz.yaml" file');

            return 1;
        }

        $url = $services[$serviceName];

        $io->success('Opened URL for "' . $serviceName . '" ' . $url);

        $this->getToolkit()->sendRPCCommand(new OpenURLRpcCommand($url));

        return 0;
    }
}
