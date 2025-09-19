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

namespace BaksDev\Services\UseCase\Admin\New\Date;

use BaksDev\Services\Entity\Event\Date\ServiceDateInterface;
use BaksDev\Services\Entity\Event\Info\ServiceInfoInterface;
use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Types\DateIntervalType;
use Doctrine\DBAL\Types\TimeType;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ServiceInfoEvent */
final class ServiceDateDTO implements ServiceDateInterface
{

    /** Даты  */
    private ?DateTimeImmutable $date = null;

    private ?DateTimeImmutable $time = null;

//    private ?DateIntervalType $duration = null;
//    private ?DateTimeImmutable $duration = null;
    private ?DateInterval $duration = null;

    /**
     * date
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date ?: new DateTimeImmutable('now');
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * To
     */
    //    public function getTo(): DateTimeImmutable
    //    {
    //        return $this->to ?: new DateTimeImmutable('now');
    //    }
    //
    //    public function setTo(DateTimeImmutable $to): self
    //    {
    //        $this->to = $to;
    //
    //        return $this;
    //    }

//    public function getDuration(): ?DateIntervalType
//    public function getDuration(): ?DateTimeImmutable
    public function getDuration(): ?DateInterval
    {
        return $this->duration;
    }


//    public function setDuration(DateIntervalType|string|null $duration): self
    public function setDuration(DateInterval|string|null $duration): self
    {

//        $this->duration = $duration;
        // TODO
//        $this->duration = is_string($duration) ? new  DateIntervalType($duration) : $duration;
        $this->duration = is_string($duration) ? new  DateInterval($duration) : $duration;

//        $this->duration = null;

        return $this;
    }


    public function getTime(): ?DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(DateTimeImmutable|int|string|null $time): self
    {
        $this->time = is_int($time) || is_string($time) ? new DateTimeImmutable( $time) : $time;
        return $this;
    }



}