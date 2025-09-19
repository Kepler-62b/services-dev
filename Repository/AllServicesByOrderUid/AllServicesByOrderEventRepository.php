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
 *
 */

declare(strict_types=1);

namespace BaksDev\Services\Repository\AllServicesByOrderUid;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Orders\Order\Entity\Event\OrderEvent;
use BaksDev\Orders\Order\Entity\Services\OrderService;
use BaksDev\Orders\Order\Type\Event\OrderEventUid;
use BaksDev\Services\Entity\Event\Info\ServiceInfo;
use BaksDev\Services\Entity\Event\Invariable\ServiceInvariable;
use BaksDev\Services\Entity\Event\Price\ServicePrice;
use BaksDev\Services\Entity\Service;
use InvalidArgumentException;

final class AllServicesByOrderEventRepository implements AllServicesByOrderEventInterface
{
    private OrderEventUid|false $event = false;

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
    ) {}

    public function forOrderEvent(OrderEvent|OrderEventUid $event): self
    {
        if($event instanceof OrderEvent)
        {
            $event = $event->getId();
        }

        $this->event = $event;

        return $this;
    }

    public function findAll(): array|false
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        if(false === $dbal->isProjectProfile())
        {
            throw new InvalidArgumentException('Не установлен PROJECT_PROFILE');
        }

        $dbal
            ->addSelect('service.id AS service_id')
            ->from(Service::class, 'service');

        $dbal
            //            ->addSelect('orders_service.service AS order_service')
            ->join(
                'service',
                OrderService::class,
                'orders_service',
                'orders_service.service = service.id'
            );

        $dbal
            //                        ->addSelect('service_invariable.profile')
            ->join(
                'orders_service',
                ServiceInvariable::class,
                'service_invariable',
                '
                        service_invariable.main = orders_service.service
                        AND
                        service_invariable.profile = :'.$dbal::PROJECT_PROFILE_KEY
            );

        $dbal
            ->addSelect('service_price.price')
            ->addSelect('service_price.currency')
            ->join(
                'service_invariable',
                ServicePrice::class,
                'service_price',
                'service_price.event = service_invariable.event'
            );

        $dbal
            ->addSelect('service_info.name')
            ->addSelect('service_info.preview')
            ->join(
                'service_invariable',
                ServiceInfo::class,
                'service_info',
                'service_info.event = service_invariable.event'
            );


        return $dbal->fetchAllAssociative();
    }
}