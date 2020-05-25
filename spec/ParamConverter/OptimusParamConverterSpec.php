<?php

declare(strict_types=1);

namespace spec\Ckrack\OptimusBundle\ParamConverter;

use Ckrack\OptimusBundle\ParamConverter\OptimusParamConverter;
use Jenssegers\Optimus\Optimus;
use PhpSpec\ObjectBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class OptimusParamConverterSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Optimus(2123809381, 1885413229, 146808189), false);
    }

    public function it_is_initializable(Optimus $optimus)
    {
        $this->beConstructedWith($optimus, false);

        $this->shouldHaveType(OptimusParamConverter::class);
    }

    public function is_supports_all_params()
    {
        $configuration = new ParamConverter([]);

        $this->supports($configuration)->shouldReturn(true);
    }

    public function it_decodes_when_optimus_is_in_request()
    {
        $request = new Request([], [], ['optimus' => '1795633817']);
        $configuration = new ParamConverter([]);

        $this->supports($configuration)->shouldReturn(true);
        $this->apply($request, $configuration)->shouldReturn(true);
        expect($request->attributes->get('optimus'))->toBe(20);
    }

    public function it_decodes_when_optimus_is_named_in_ParamConverter_options()
    {
        $request = new Request([], [], ['controllerArgument' => '1795633817']);
        $configuration = new ParamConverter(['options' => ['optimus' => 'controllerArgument']]);

        $this->supports($configuration)->shouldReturn(true);
        $this->apply($request, $configuration)->shouldReturn(true);
        expect($request->attributes->get('controllerArgument'))->toBe(20);
    }

    public function it_does_not_decode_when_there_is_no_optimus()
    {
        $request = new Request([], [], ['controllerArgument' => '1795633817']);
        $configuration = new ParamConverter([]);
        $configuration->setName('controllerArgument');

        $this->supports($configuration)->shouldReturn(true);
        expect($request->attributes->get('controllerArgument'))->toBe('1795633817');
    }

    public function it_passthrough_when_argument_is_true(Optimus $optimus)
    {
        $this->beConstructedWith($optimus, true);

        $request = new Request();
        $configuration = new ParamConverter([]);
        $configuration->setName('controllerArgument');

        $this->apply($request, $configuration)->shouldReturn(false);
    }

    public function it_does_not_passthrough_when_argument_is_false(Optimus $optimus)
    {
        $this->beConstructedWith($optimus, false);

        $request = new Request();
        $configuration = new ParamConverter([]);
        $configuration->setName('controllerArgument');

        $this->apply($request, $configuration)->shouldReturn(true);
    }
}
