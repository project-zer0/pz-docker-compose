# Project Zer0 Docker Compose Module

A pz module for Docker Compose

## Install

Via composer:

```shell
$ composer require --dev project-zer0/pz-docker-compose
```

## Configuration

This module provides following config block to `.pz.yaml` file

```yaml
project-zer0:
    docker_compose:
        project_name: project-zer0 # Project name to use for docker-compose
        operations:
            up:
                down: false # run docker-compose down before running up
                force_recreate: false # force container recreation
                detach: true # starts docker-compose services in background
                build: true # builds containers on start
                pull: true # pulls newest images
        files: # defines env's to use with --env flag in commands, default env is dev
            dev: ./docker/docker-compose.dev.yaml
```

## Commands

This module provides these commands in `pz` tool

```shell
$ pz docker:compose          [dc|compose] Define and run multi-container applications with Docker.
$ pz docker:compose:down     [down] Stop and remove containers, networks, images, and volumes
$ pz docker:compose:logs     [logs] View output from containers.
$ pz docker:compose:restart  [restart] Restart running containers.
$ pz docker:compose:start    [start] Start existing containers.
$ pz docker:compose:stop     [stop] Stop running containers without removing them.
$ pz docker:compose:up       [up] Builds, (re)creates, starts, and attaches to containers for application
```

## Testing

Run test cases

```bash
$ composer test
```

Run test cases with coverage (HTML format)

```bash
$ composer test-coverage
```

Run PHP style checker

```bash
$ composer cs-check
```

Run PHP style fixer

```bash
$ composer cs-fix
```

Run all continuous integration tests

```bash
$ composer ci-run
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## License

Please see [License File](LICENSE) for more information.
