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

namespace BaksDev\Services\UseCase\Admin\Add;

use BaksDev\Orders\Order\Type\ServiceUid;
use BaksDev\Services\Type\Period\ServicePeriodUid;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ServiceBasketForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('period', HiddenType::class);

        $builder->get('period')->addModelTransformer(
            new CallbackTransformer(
                function($period) {
                    return $period instanceof ServicePeriodUid ? $period->getValue() : $period;
                },
                function($period) {
                    return $period ? new ServicePeriodUid($period) : null;
                }
            )
        );

        $builder->add('service', HiddenType::class);

        $builder->get('service')->addModelTransformer(
            new CallbackTransformer(
                function($service) {
                    return $service instanceof ServiceUid ? $service->getValue() : $service;
                },
                function($service) {
                    return $service ? new ServiceUid($service) : null;
                }
            )
        );

        $builder->add('date', HiddenType::class);

        $builder->get('date')->addModelTransformer(
            new CallbackTransformer(
                function($date) {
                    return $date instanceof DateTimeImmutable ? $date->format('Y-m-d') : $date;
                },
                function($date) {
                    return $date ? new DateTimeImmutable($date) : null;
                }
            )
        );

//        $builder->add(
//            'date',
//            DateType::class,
//            [
//                'widget' => 'single_text',
//                'required' => false,
//                'label' => 'Время от',
//                'input' => 'datetime_immutable',
//                'attr' => [
////                    'disabled' => true,
//                ]
//            ]
//        );

        /* Сохранить ******************************************************/
        $builder->add(
            'add',
            SubmitType::class,
            ['label' => 'Save', 'label_html' => true, 'attr' => ['class' => 'btn-primary']]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceBasketDTO::class,
            'method' => 'POST',
            'attr' => ['class' => 'w-100'],
        ]);
    }
}