<?php

declare(strict_types=1);

namespace BaksDev\Services\UseCase\Admin\Add;

use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Services\Type\Period\ServicePeriodUid;
use DateTimeImmutable;


final class ServiceBasketDTO /*implements ServiceEventInterface*/
{

    private ?ServicePeriodUid $period = null;
    private ?ServiceUid $service = null;

    private DateTimeImmutable|string $date;


    public function getPeriod(): ?ServicePeriodUid
    {
        return $this->period;
    }

    public function setPeriod(?ServicePeriodUid $period): self
    {
        $this->period = $period;
        return $this;
    }

    public function getService(): ?ServiceUid
    {
        return $this->service;
    }

    public function setService(?ServiceUid $service): self
    {
        $this->service = $service;
        return $this;
    }

    public function getDate(): DateTimeImmutable|string
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable|string $date): self
    {
        $this->date = is_string($date) ? new DateTimeImmutable($date) : $date;

        return $this;
    }
}