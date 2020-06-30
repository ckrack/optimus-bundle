# OptimusBundle

Integrates [jenssegers/optimus](https://github.com/jenssegers/optimus) in a Symfony project.

## Installation using composer

These commands requires you to have Composer installed globally.
Open a command console, enter your project directory and execute the following
commands to download the latest stable version of this bundle:

### Using Symfony Flex

```bash
    composer config extra.symfony.allow-contrib true
    composer require ckrack/optimus-bundle
```

### Using Symfony 4 Framework

```bash
    composer require ckrack/optimus-bundle
```

If this has not been done automatically, enable the bundle by adding the
following line in the `config/bundles.php` file of your project:

```php
<?php

return [
    …,
    Ckrack\OptimusBundle\CkrackOptimusBundle::class => ['all' => true],
];
```

## Configuration

The configuration (`config/packages/ckrack_optimus.yaml`) looks as follows:

```yaml
ckrack_optimus:
    # Set options, as documented at https://github.com/jenssegers/optimus#usage
    prime: "%env(int:OPTIMUS_PRIME)%"
    inverse: "%env(int:OPTIMUS_INVERSE)%"
    random: "%env(int:OPTIMUS_RANDOM)%"

    # if set to true, param converter will continue with the next available param converters
    passthrough: true

```

To generate the env variables, we can use optimus' `spark` command.

```bash
vendor/bin/optimus spark -f env
```

## Usage

```php
$optimus = $this->get('optimus');
```

## Optimus Param Converter

Converter Name: `optimus.converter`

The optimus param converter attempts to convert `optimus` attribute set in the route into an integer parameter.

```php
/**
 * @Route("/users/{optimus}")
 */
public function getAction(int $optimus)
{
}
```

For specific case, just add `"optimus" = "{parameter_name}"` in ParamConverter
options:

```php
/**
 * @Route("/users/{user}")
 * @ParamConverter("user", options={"optimus" = "user"})
 */
public function getAction(int $user)
{
}
```


### Using Passthrough

`Passthrough` allows to continue with the next available param converters.
So if you would like to retrieve an Entity instead of an integer, just activate
passthrough :

```yaml
ckrack_optimus:
    passthrough: true
```

Based on the example above:

```php
/**
 * @Route("/users/{user}")
 * @ParamConverter("user")
 */
public function getAction(User $user)
{
}
```

As you can see, the passthrough feature allows to use `DoctrineParamConverter`
or any another `ParamConverter` you would have created.

## Twig Extension

The Twig extension provides optimus generation inside of templates.

### Usage

```twig
{{ path('users.show', {'optimus': user.id | optimus }) }}
```
