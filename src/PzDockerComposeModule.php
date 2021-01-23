<?php

declare(strict_types=1);

namespace ProjectZer0\PzDockerCompose;

use ProjectZer0\Pz\Config\PzModuleConfigurationInterface;
use ProjectZer0\Pz\Module\PzModule;
use ProjectZer0\Pz\ProjectZer0Toolkit;
use ProjectZer0\PzDockerCompose\Command\DockerComposeCommand;
use ProjectZer0\PzDockerCompose\Command\DownCommand;
use ProjectZer0\PzDockerCompose\Command\LogsCommand;
use ProjectZer0\PzDockerCompose\Command\RestartCommand;
use ProjectZer0\PzDockerCompose\Command\StartCommand;
use ProjectZer0\PzDockerCompose\Command\StopCommand;
use ProjectZer0\PzDockerCompose\Command\UpCommand;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @author Aurimas Niekis <aurimas@niekis.lt>
 */
class PzDockerComposeModule extends PzModule
{
    public function getCommands(): array
    {
        return [
            new DockerComposeCommand(),
            new UpCommand(),
            new DownCommand(),
            new StartCommand(),
            new StopCommand(),
            new RestartCommand(),
            new LogsCommand(),
        ];
    }

    public function boot(ProjectZer0Toolkit $toolkit): void
    {
    }

    public function getConfiguration(): ?PzModuleConfigurationInterface
    {
        return new class() implements PzModuleConfigurationInterface {
            public function getConfigurationNode(): NodeDefinition
            {
                $treeBuilder = new TreeBuilder('docker_compose');

                $node = $treeBuilder->getRootNode()
                    ->isRequired()
                    ->children()
                        ->scalarNode('project_name')
                            ->isRequired()
                        ->end()
                        ->arrayNode('files')
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->scalarPrototype()
                            ->end()
                        ->end()
                        ->arrayNode('operations')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('up')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->booleanNode('down')
                                        ->defaultFalse()
                                    ->end()
                                    ->booleanNode('force_recreate')
                                        ->defaultFalse()
                                    ->end()
                                    ->booleanNode('detach')
                                        ->defaultTrue()
                                    ->end()
                                    ->booleanNode('build')
                                        ->defaultTrue()
                                    ->end()
                                    ->booleanNode('pull')
                                        ->defaultTrue()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

                return $node;
            }
        };
    }

    public function getName(): string
    {
        return 'docker-compose';
    }
}
