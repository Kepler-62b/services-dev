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

namespace BaksDev\Services\Messenger;

use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Services\Type\Event\ServiceEventUid;

final class ServiceMessage
{
    /** Идентификатор */
    private string $id;

    /** Идентификатор события */
    private string $event;

    /** Идентификатор предыдущего события */
    private ?string $last;

    public function __construct(ServiceUid $id, ServiceEventUid $event, ?ServiceEventUid $last = null)
    {
        $this->id = (string) $id;
        $this->event = (string) $event;
        $this->last = $last ? (string) $last : null;
    }

    /** Идентификатор */
    public function getId(): ServiceUid
    {
        return new ServiceUid($this->id);
    }

    /** Идентификатор события */
    public function getEvent(): ServiceEventUid
    {
        return new ServiceEventUid($this->event);
    }

    /** Идентификатор предыдущего события */
    public function getLast(): ?ServiceEventUid
    {
        return $this->last ? new ServiceEventUid($this->last) : null;
    }
}