<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MathExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('percentageChange', [$this, 'percentageChange'])
        ];
    }

    public function percentageChange(int $x, int $y)
    {
        if ($y <= 0) {
            $y = 1;
        }

        return round((($x - $y)/$y) * 100);
    }
}