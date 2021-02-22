<?php

declare(strict_types=1);

namespace App\Contract;

class Classroom
{
    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var bool|null
     */
    public ?bool $isActive = null;
}