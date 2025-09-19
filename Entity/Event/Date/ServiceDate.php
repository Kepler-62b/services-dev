<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Services\Entity\Event\Date;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Services\Entity\Event\ServiceEvent;
use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateIntervalType;
use Doctrine\DBAL\Types\TimeImmutableType;
use Doctrine\DBAL\Types\TimeType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/* ServiceInfo */

#[ORM\Entity]
#[ORM\Table(name: 'service_date')]
class ServiceDate extends EntityEvent
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: ServiceEvent::class, inversedBy: 'date')]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    private ServiceEvent $event;


    /** Дата услуги */
    // TODO Возможно отдельная сущность ???
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $date;

    /** Время услуги */
//    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
//    private ?TimeType $time;
    private ?DateTimeImmutable $time;

    /** Продолжительность услуги */
    #[ORM\Column(type: Types::DATEINTERVAL, nullable: true)]
//    private ?DateIntervalType $duration;
    private ?DateInterval $duration;


    public function __construct(ServiceEvent $event)
    {
        $this->event = $event;
    }

    public function __toString(): string
    {
        return (string) $this->event;
    }

    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

        if($dto instanceof ServiceDateInterface)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if($dto instanceof ServiceDateInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }



}