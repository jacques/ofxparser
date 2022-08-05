<?php declare(strict_types=1);

namespace OfxParser\Entities;

final class Statement extends AbstractEntity
{
    /**
     * @var string
     */
    public $currency;

    /**
     * @var Transaction[]
     */
    public $transactions;

    /**
     * @var \DateTimeInterface
     */
    public $startDate;

    /**
     * @var \DateTimeInterface
     */
    public $endDate;
}
