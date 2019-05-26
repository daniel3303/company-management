<?php

namespace App\Form\Employee;

use App\Entity\Employee\Employee;
use App\Entity\Employee\WorkDay;
use App\Entity\Employee\WorkInterval;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var $entity WorkDay
         */
        $entity = $builder->getData();

        $builder->add('employee', EntityType::class, [
                'class' => Employee::class,
                'label' => 'Funcionário',
                'choice_label' => 'name',
            ])
            ->add('day', DateType::class, [
                'label' => 'Dia',
                'widget' => 'single_text',
            ])
            ->add('workIntervals', CollectionType::class, [
                'label' => 'Período de Trabalho',
                'entry_type' => WorkIntervalType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add("note", TextareaType::class, [
                'label' => 'Notas',
                'help' => 'Este campo é opcional.',
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkDay::class,
        ]);
    }
}
