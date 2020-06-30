<?php

declare(strict_types=1);

namespace Ckrack\OptimusBundle\Twig;

use Jenssegers\Optimus\Optimus;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class OptimusExtension extends AbstractExtension
{
    /**
     * @var Optimus
     */
    private $optimus;

    public function __construct(Optimus $optimus)
    {
        $this->optimus = $optimus;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('optimus', [$this, 'optimusEncode']),
        ];
    }

    public function optimusEncode(int $number): int
    {
        return $this->optimus->encode($number);
    }
}
