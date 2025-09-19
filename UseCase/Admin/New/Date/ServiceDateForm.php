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

use BaksDev\Services\UseCase\Admin\New\Info\ServiceInfoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ServiceDateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('date', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'required' => false,
            'format' => 'dd.MM.yyyy',
            'input' => 'datetime_immutable',
            'label' => 'Дата'
        ]);

//        $builder->add('time', TimeType::class, [
////            'input'  => 'datetime',
//            'input'  => 'timestamp',
//            'widget' => 'choice',
//            'label' => 'Время'
//        ]);

        $builder->add(
            'time',
            TimeType::class,
            [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Время',
                'input' => 'datetime_immutable',
            ]
        );


        $builder->add(
            'time',
            TimeType::class,
            [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Время',
                'input' => 'datetime_immutable',
            ]
        );


//        $builder->get('time')->addModelTransformer(
//            new CallbackTransformer(
//                function($time) {
//                    return $time instanceof TimeType ? $time->getValue() : $time;
//                },
//                function($time) {
//
//                    return new TimeType($time);
//                }
//            )
//        );

        /*
        $builder->add('from', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'required' => false,
            'format' => 'dd.MM.yyyy hh:mm',
            'input' => 'datetime_immutable',
            'label' => 'Дата'
        ]);
        */


        //        $builder->add('to', DateType::class, [
        //            'widget' => 'single_text',
        //            'html5' => false,
        //            'attr' => ['class' => 'js-datepicker'],
        //            'required' => false,
        //            'format' => 'dd.MM.yyyy hh:mm',
        //            'input' => 'datetime_immutable',
        //            'label' => 'Дата до'
        //        ]);
        //

        $builder->add('duration', DateIntervalType::class, [
            'input' => 'string', // Store as ISO 8601 string
            //            'widget' => 'single_text', // Render as multiple select fields
            'widget' => 'choice', // Render as multiple select fields
            'with_years' => false,
            'with_months' => false,
            'with_days' => false,
            'with_hours' => true,
            'with_minutes' => true,
            //            'with_seconds' => true,
            'label' => 'Продолжительность',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceDateDTO::class,
        ]);
    }
}