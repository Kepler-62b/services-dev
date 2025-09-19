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

namespace BaksDev\Services\Repository\CurrentServiceEvent;

use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Orders\Order\Entity\Event\OrderEvent;
use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Services\Entity\Event\ServiceEvent;
use BaksDev\Services\Entity\Service;

final class CurrentServiceEventRepository implements CurrentServiceEventInterface
{
    private ServiceUid|false $service = false;

    public function __construct(
        private readonly ORMQueryBuilder $ORMQueryBuilder
    ) {}

    public function byService(Service|ServiceUid $service): self
    {
        if($service instanceof Service)
        {
            $service = $service->getId();
        }

        $this->service = $service;

        return $this;
    }

    /**
     * Метод возвращает текущее активное событие заказа
     */
    public function find(): OrderEvent|false
    {
        $orm = $this->ORMQueryBuilder->createQueryBuilder(self::class);

        $orm
            ->from(Service::class, 'service')
            ->where('service.id = :service')
            ->setParameter(
                key: 'service',
                value: $this->service,
                type: ServiceUid::TYPE
            );

        $orm
            ->select('event')
            ->join(
                ServiceEvent::class,
                'event',
                'WITH',
                'event.id = service.event'
            );

        return $orm->getOneOrNullResult() ?: false;
    }
}