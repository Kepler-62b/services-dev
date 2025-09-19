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

namespace BaksDev\Services\Repository\ServicePeriods;

use BaksDev\Orders\Order\Type\Event\OrderEventUid;
use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Reference\Currency\Type\Currency;
use BaksDev\Reference\Money\Type\Money;
use BaksDev\Services\Type\Event\ServiceEventUid;
use BaksDev\Services\Type\Period\ServicePeriodUid;
use DateTimeImmutable;

final class ServicePeriodResult
{

    public function __construct(
        private string $id,
        private string $service_id,
        //        private string $main,
        private string $time_from,
        private string $time_to,

        private ?string $service_event,

        //        private ?string $order_event,
        //        private ?string $service_date,

        private ?string $order_services,
        //        private ?string $order_service_serv,

        private int|null $service_price,
        private string|null $service_currency,
    ) {}


    //    public function getOrderServices(): ?string
    //    {
    //            return $this->order_services;
    //    }

    public function getOrderServices(): ?array
    {

        if(is_null($this->order_services))
        {
            return null;
        }

        if(false === json_validate($this->order_services))
        {
            return null;
        }

        $order_services = json_decode($this->order_services, null, 512, JSON_THROW_ON_ERROR);

        if(null === current($order_services))
        {
            return null;
        }

        // TODO Сортировка массива элементов по дате ?
        //        usort($order_services, function($curr, $next) {
        //              return $curr->order_service_date <=> $next ->order_service_date;
        //        });

        return $order_services;

    }

    // TODO remove
    public function getServiceDate(): ?DateTimeImmutable
    {
        return new DateTimeImmutable($this->service_date);
    }

    public function getServiceId(): ServiceUid
    {
        return new ServiceUid($this->service_id);
    }

    public function getServiceEvent(): ServiceEventUid
    {
        return new ServiceEventUid($this->service_event);
    }


    //    public function getOrderServiceServ(): ?string
    //    {
    //        return $this->order_service_serv ? new OrderEventUid($this->order_event) : null;
    //    }

    // TODO remove
    public function getOrderEvent(): ?OrderEventUid
    {
        return $this->order_event ? new OrderEventUid($this->order_event) : null;
    }

    public function getPeriodId(): ServicePeriodUid
    {
        return new ServicePeriodUid($this->id);
    }

    public function getTimeFrom(): ?DateTimeImmutable
    {
        return new DateTimeImmutable($this->time_from);
        // return (new DateTimeImmutable($this->time_from))->format('H:i');
    }

    public function getTimeTo(): ?DateTimeImmutable
    {
        return new DateTimeImmutable($this->time_to);
    }


    public function getServicePrice(): Money
    {
        // TODO без применения скидки в профиле пользователя
        //        if(is_null($this->profile_discount))
        //        {
        //            return new Money($this->service_price, true);
        //        }

        // TODO применяем скидку пользователя из профиля

        $price = new Money($this->service_price, true);

        //        $price->applyString($this->profile_discount);

        return $price;
    }

    public function getServiceCurrency(): Currency|bool
    {
        return new Currency($this->service_currency);
    }
}