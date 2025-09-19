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

namespace BaksDev\Services\UseCase\Admin\New\Period;

use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

final class ServicePeriodForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add(
            'frm',
            TimeType::class,
            [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Время от',
                'input' => 'datetime_immutable',
            ]
        );

        $format = "H:i";
        /** @var ServicePeriodDTO $dto  */
        $now = new DateTimeImmutable('now');

        $builder->get('frm')->addModelTransformer(
            new CallbackTransformer(
                function($frm) {
                    return $frm;
                },
                function($frm) use ($now, $format) {
//                    $time = date_parse_from_format($format, $frm->format($format)); /* парсим время */
                    $getFrm = $frm
//                        ->setTime($time['hour'], $time['minute'])
                        ->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));
                    return $getFrm;
                }
            )
        );

        $builder->add(
            'upto',
            TimeType::class,
            [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Время до',
                'input' => 'datetime_immutable',

//                'attr' => [
//                    'step' => 900,
//                    'min'=>"12:00",
//                    'max'=>"18:00"
//                ],

//                 'html5' => false,

                // TODO
//                'widget' => 'choice',
//                'minutes' => range(0, 45, 15),
//                'hours' => range(9, 18, 1)
            ]
        );

        $builder->get('upto')->addModelTransformer(
            new CallbackTransformer(
                function($upto) {
                    return $upto;
                },
                function($upto) use ($now, $format) {
//                    $time = date_parse_from_format($format, $upto->format($format)); /* парсим время */
                    $getUpto = $upto
//                        ->setTime($time['hour'], $time['minute'])
                        ->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));
                    return $getUpto;
                }
            )
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


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServicePeriodDTO::class,
        ]);
    }
}