<?php

declare(strict_types=1);

namespace Bavix\Wallet\Simple;

use Bavix\Wallet\Interfaces\Mathable;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Illuminate\Config\Repository;

class BrickMath implements Mathable
{
    protected int $scale;

    public function __construct(Repository $configRepository)
    {
        $this->scale = (int) $configRepository
            ->get('wallet.math.scale', 64)
        ;
    }

    public function add($first, $second, ?int $scale = null): string
    {
        return (string) BigDecimal::of($first)
            ->plus(BigDecimal::of($second))
            ->toScale($this->scale($scale), RoundingMode::DOWN)
        ;
    }

    public function sub($first, $second, ?int $scale = null): string
    {
        return (string) BigDecimal::of($first)
            ->minus(BigDecimal::of($second))
            ->toScale($this->scale($scale), RoundingMode::DOWN)
        ;
    }

    public function div($first, $second, ?int $scale = null): string
    {
        return (string) BigDecimal::of($first)
            ->dividedBy(BigDecimal::of($second), $this->scale($scale), RoundingMode::DOWN)
        ;
    }

    public function mul($first, $second, ?int $scale = null): string
    {
        return (string) BigDecimal::of($first)
            ->multipliedBy(BigDecimal::of($second))
            ->toScale($this->scale($scale), RoundingMode::DOWN)
        ;
    }

    public function pow($number, int $exponent, ?int $scale = null): string
    {
        return (string) BigDecimal::of($number)
            ->power($exponent)
            ->toScale($this->scale($scale), RoundingMode::DOWN)
        ;
    }

    public function ceil($number): string
    {
        return (string) BigDecimal::of($number)
            ->dividedBy(BigDecimal::one(), 0, RoundingMode::CEILING)
        ;
    }

    public function floor($number): string
    {
        return (string) BigDecimal::of($number)
            ->dividedBy(BigDecimal::one(), 0, RoundingMode::FLOOR)
        ;
    }

    public function round($number, int $precision = 0): string
    {
        return (string) BigDecimal::of($number)
            ->dividedBy(BigDecimal::one(), $precision, RoundingMode::HALF_UP)
        ;
    }

    public function abs($number): string
    {
        return (string) BigDecimal::of($number)->abs();
    }

    public function negative($number): string
    {
        return (string) BigDecimal::of($number)->negated();
    }

    public function compare($first, $second): int
    {
        return BigDecimal::of($first)->compareTo(BigDecimal::of($second));
    }

    protected function scale(?int $scale = null): int
    {
        return $scale ?? $this->scale;
    }
}
