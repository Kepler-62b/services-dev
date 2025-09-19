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

namespace BaksDev\Services\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Core\Type\UidType\ParamConverter;
use BaksDev\Delivery\Type\Event\DeliveryEventUid;
use BaksDev\Delivery\Type\Id\Choice\TypeDeliveryPickup;
use BaksDev\Delivery\Type\Id\DeliveryUid;
use BaksDev\Orders\Order\Entity\Order;
use BaksDev\Orders\Order\Forms\OrderService\AddOrderServiceToOrderDTO;
use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Orders\Order\UseCase\Public\Basket\Add\Service\OrderServiceDTO;
use BaksDev\Orders\Order\UseCase\Public\Basket\Add\Service\OrderServiceForm;
use BaksDev\Orders\Order\UseCase\Public\Basket\OrderDTO;
use BaksDev\Orders\Order\UseCase\Public\Basket\OrderHandler;
use BaksDev\Orders\Order\UseCase\Public\Basket\User\Delivery\OrderDeliveryDTO;
use BaksDev\Orders\Order\UseCase\Public\Basket\User\Payment\OrderPaymentDTO;
use BaksDev\Payment\Type\Id\Choice\TypePaymentCache;
use BaksDev\Payment\Type\Id\PaymentUid;
use BaksDev\Services\Type\Event\ServiceEventUid;
use BaksDev\Services\Type\Period\ServicePeriodUid;
use BaksDev\Users\User\Type\Id\UserUid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DateTimeValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_SERVICE_NEW')]
final class ServiceToOrderController extends AbstractController
{
    #[Route('/admin/service/basket/add/{date}', name: 'admin.basket', methods: ['GET', 'POST'])]
    public function basket(
        Request $request,
        //        ServiceHandler $serviceHandler,
        OrderHandler $handler,

        #[ParamConverter(ServiceUid::class, key: 'service')] $service,
        #[ParamConverter(DateTimeValueResolver::class)] $date = null,
        #[ParamConverter(ServicePeriodUid::class)] $period = null,
        #[ParamConverter(ServiceEventUid::class)] $event = null,
        #[Autowire(env: 'PROJECT_USER')] string|null $projectUser = null
    ): Response
    {


        //        dd($order_date);


        //
        $OrderDTO = new OrderDTO();

        /** Присваиваем пользователя (клиента) */
        $OrderUserDTO = $OrderDTO->getUsr();
        $OrderUserDTO->setUsr($this->getUsr()?->getId() ?: new UserUid());


        $OrderPaymentDTO = new OrderPaymentDTO();
        $OrderPaymentDTO->setPayment(new PaymentUid(TypePaymentCache::class));

        $OrderUserDTO->setPayment($OrderPaymentDTO);

        $OrderDeliveryDTO = new OrderDeliveryDTO();
        $OrderDeliveryDTO->setDelivery(new DeliveryUid(TypeDeliveryPickup::class));
        $OrderDeliveryDTO->setEvent(new DeliveryEventUid());
        $OrderUserDTO->setDelivery($OrderDeliveryDTO);

        //        dd($OrderUserDTO);

        //        $OrderDTO->setUsr($OrderUserDTO);


        // TODO OrderServiceDTO = new OrderServiceDTO()
        //        $ServiceBasketDTO = new ServiceBasketDTO();
        //        $ServiceBasketDTO
        //            ->setService($service)
        //            ->setPeriod($period)
        //            ->setDate($date);

        $AddOrderServiceToOrderDTO = new AddOrderServiceToOrderDTO();
        $AddOrderServiceToOrderDTO
            ->setServ($service)
            ->setPeriod($period)
            ->setDate($date);


        $OrderInvariable = $OrderDTO->getInvariable();
        $OrderInvariable->setUsr($projectUser);

        // Услуги
        $services = new ArrayCollection();
        $services->add($AddOrderServiceToOrderDTO);


//        $OrderDTO->

        $OrderDTO->setServ($services);


        //        $OrderDTO->s

        //        $ServicePeriodDTO = new ServicePeriodDTO();
        //        $ServiceDTO->addPeriod($ServicePeriodDTO);
        //
        //
        //        /* Заполнить профиль */
        //        $ServiceInvariableDTO = new ServiceInvariableDTO();
        //        $ServiceInvariableDTO->setProfile($this->getCurrentProfileUid());
        //        //
        //        $ServiceDTO->setInvariable($ServiceInvariableDTO);


        /** Форма */

        $form = $this
            ->createForm(
                type: OrderServiceForm::class,
                data: $OrderServiceDTO,
                //                options: ['action' => $this->generateUrl('admin.service'),],

                options: ['action' => $this->generateUrl(
                    route: 'services:admin.basket',
                    parameters: [
                        'service' => $service,
                        'period' => $period,
                        'date' => $date,
                        'event' => $event,
                    ]
                ),]
            )
            ->handleRequest($request);

        //        $form = $this->createForm(ServiceBasketForm::class, $ServiceBasketDTO);
        //        $form->handleRequest($request);

        //        dd($ServiceBasketDTO);
        //        dd($event);


//        dd($OrderDTO);

        // TODO
        if($form->isSubmitted() && $form->isValid() && $form->has('add'))
        {

//            dd($OrderDTO);
//                        dd($OrderServiceDTO);


            $this->refreshTokenForm($form);

            //            $handle = $serviceHandler->handle($ServiceBasketDTO);

            //            $OrderDTO->set

            $handle = $handler->handle($OrderDTO);

            $this->addFlash
            (
                'page.new',
                $handle instanceof Order ? 'success.new' : 'danger.new',
                'service.admin',
                $handle
            );

            return $handle instanceof Order ? $this->redirectToRoute('services:admin.service',
                parameters: ['id' => $event]) : $this->redirectToReferer();
        }

        return $this->render(['form' => $form->createView()]);

    }
}