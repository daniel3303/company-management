<?php

namespace App\Form\Employee;

use App\Entity\Employee\WorkInterval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkIntervalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateTimeType::class, [
                'label' => 'Hora de Entrada',
                'widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Hora de Saída',
                'widget' => 'single_text',
                'with_seconds' => false,
            ])
            ->add('hourlyWage', MoneyType::class, [
                'label' => 'Vencimento/Hora',
                'required' => false,
                'help' => 'Caso não preencha o sistema cálcula.'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkInterval::class,
        ]);
    }
}
