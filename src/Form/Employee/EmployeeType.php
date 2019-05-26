<?php

namespace App\Form\Employee;

use App\Entity\Employee\Employee;
use App\Entity\Employee\WorkDay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, ['label' => 'Nome'])
            ->add('workingHoursPerDay', NumberType::class, ['label' => 'Horas de trabalho obrigatórias'])
            ->add('extraHourlyWage', MoneyType::class, ['label' => 'Salário por hora extraordinária'])
            ->add('hourlyWage', MoneyType::class, ['label' => 'Salário por hora normal'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
