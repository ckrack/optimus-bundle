<?php

declare(strict_types=1);

namespace Ckrack\OptimusBundle\ParamConverter;

use Jenssegers\Optimus\Optimus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class OptimusParamConverter implements ParamConverterInterface
{
    /**
     * @var Optimus
     */
    protected $optimus;

    /**
     * @var bool
     */
    private $passthrough;

    public function __construct(Optimus $optimus, bool $passthrough)
    {
        $this->optimus = $optimus;
        $this->passthrough = $passthrough;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $this->setOptimus($request, $configuration);
        $this->removeOptimusOption($configuration);

        return $this->continueWithNextParamConverters();
    }

    public function supports(ParamConverter $configuration): bool
    {
        // always support, because we can't check the request here.
        return true;
    }

    private function setOptimus(Request $request, ParamConverter $configuration): void
    {
        $identifier = $this->getIdentifier(
            $request,
            array_replace(['optimus' => null], $configuration->getOptions()),
            (string) $configuration->getName()
        );

        if (!$identifier) {
            return;
        }

        try {
            $optimus = $this->optimus->decode((int) $request->attributes->get($identifier));
            $request->attributes->set($identifier, $optimus);
        } catch (\Throwable $th) {
            return;
        }
    }

    private function getIdentifier(Request $request, $options, string $name): ?string
    {
        if ($options['optimus'] && !\is_array($options['optimus'])) {
            return $options['optimus'];
        }

        if ($request->attributes->has('optimus')) {
            return 'optimus';
        }

        return null;
    }

    private function removeOptimusOption(ParamConverter $configuration): void
    {
        $options = $configuration->getOptions();

        if (isset($options['optimus'])) {
            unset($options['optimus']);
            $configuration->setOptions($options);
        }
    }

    private function continueWithNextParamConverters(): bool
    {
        return !$this->passthrough;
    }
}
