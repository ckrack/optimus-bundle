<?php

declare(strict_types=1);

namespace spec\Ckrack\OptimusBundle\Twig;

use Ckrack\OptimusBundle\Twig\OptimusExtension;
use Jenssegers\Optimus\Optimus;
use PhpSpec\ObjectBehavior;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class OptimusExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable(Optimus $optimus)
    {
        $this->beConstructedWith($optimus);

        $this->shouldHaveType(OptimusExtension::class);
    }

    public function it_encodes_in_twig_file()
    {
        $extension = new OptimusExtension(new Optimus(2123809381, 1885413229, 146808189));
        $twig = new Environment(
            new ArrayLoader(['template' => '{{ 20|optimus }}']),
            ['cache' => false, 'optimizations' => 0]
        );
        $twig->addExtension($extension);

        expect($twig->render('template'))->toBe('1795633817');
    }
}
