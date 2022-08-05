<?php declare(strict_types=1);

namespace OfxParser\Entities;

final class SignOn extends AbstractEntity
{
    /**
     * @var Status
     */
    public $status;

    /**
     * @var \DateTimeInterface
     */
    public $date;

    /**
     * @var string
     */
    public $language;

    /**
     * @var Institute
     */
    public $institute;
}
