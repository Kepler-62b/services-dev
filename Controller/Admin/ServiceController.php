<?php

namespace BaksDev\Services\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Services\Entity\Event\ServiceEvent;
use BaksDev\Services\Repository\ServicePeriods\ServicePeriodsInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_SERVICE_EDIT')]
final class ServiceController extends AbstractController
{

    #[Route('/admin/service/{id}', name: 'admin.service', defaults: ['id' => null], methods: ['GET', 'POST'])]
    public function service(
        #[MapEntity] ServiceEvent $serviceEvent,
        Request $request,
        ServicePeriodsInterface $servicePeriods
    ) {

        // Получаем список
        $periods = $servicePeriods
            ->findAll($serviceEvent->getId());

        $periods = iterator_to_array($periods);


        // Добавить "пустое значение"
        array_unshift($periods, null);


        // TODO Даты от текущего дня, на

//        $dates = function($date = 'now', $format = 'Y-m-d, D') {
        $dates = function($date = 'now', $format = 'm-d, D', $days_count = 8) {

            $dates = []; // Инициализация пустого массива для хранения дат
            $interval = new DateInterval('P1D'); // Интервал в 1 день
            $period = new DatePeriod(new DateTime($date), $interval, $days_count); // Период в 7 дней (включая начальную дату)

            // Перебор всех дат в периоде
            foreach ($period as $dateObj) {
//                $dates[] = $dateObj->format($format); // Добавление отформатированной даты в массив
                $dates[] = $dateObj; // Добавление даты в массив
            }

            return $dates;
        };

        return $this->render(
            [
                'periods' => $periods,
                'dates' => $dates(),
                'name' => $serviceEvent->getInfo()->getName(),

//                'search' => $searchForm->createView(),
            ]
        );
    }


}