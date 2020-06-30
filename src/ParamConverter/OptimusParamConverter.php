<?php

declare(strict_types=1);

namespace Ckrack\OptimusBundle\ParamConverter;

use Jenssegers\Optimus\Optimus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

final class OptimusParamConverter implements ParamConverterInterface
{
    /**
     * @var Optimus
     */
    protected $optimus;

    /**
     * @var bool
     */
    private $passthrough = false;

    /**
     * @var string
     */
    private const DEFAULT_IDENTIFIER = 'optimus';

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
        if (!$this->hasIdentifier($request, array_replace([self::DEFAULT_IDENTIFIER => null], $configuration->getOptions()))) {
            return;
        }

        $identifier = $this->getIdentifier(
            $request,
            array_replace([self::DEFAULT_IDENTIFIER => null], $configuration->getOptions())
        );

        $name = $configuration->getName();
        if ($name == null && $identifier == self::DEFAULT_IDENTIFIER) {
            $name = self::DEFAULT_IDENTIFIER;
        }

        $optimus = $this->optimus->decode((int) $request->attributes->get($identifier));
        $request->attributes->set($name, $optimus);
    }

    private function getIdentifier(Request $request, array $options): string
    {
        if ($options[self::DEFAULT_IDENTIFIER] && \is_string($options[self::DEFAULT_IDENTIFIER])) {
            return $options[self::DEFAULT_IDENTIFIER];
        }

        return self::DEFAULT_IDENTIFIER;
    }

    private function hasIdentifier(Request $request, array $options): bool
    {
        if ($options[self::DEFAULT_IDENTIFIER]) {
            return true;
        }

        if ($request->attributes->has(self::DEFAULT_IDENTIFIER)) {
            return true;
        }

        return false;
    }

    private function removeOptimusOption(ParamConverter $configuration): void
    {
        $options = $configuration->getOptions();

        if (\array_key_exists(self::DEFAULT_IDENTIFIER, $options)) {
            unset($options[self::DEFAULT_IDENTIFIER]);
            $configuration->setOptions($options);
        }
    }

    private function continueWithNextParamConverters(): bool
    {
        return !$this->passthrough;
    }
}
