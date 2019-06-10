<?php

namespace App\Form\Vehicle;

use App\Entity\Vehicle\Operation;
use App\Entity\Vehicle\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'label' => 'Veículo',
                'choice_label' => function(Vehicle $vehicle){
                    return (string)("".$vehicle->getModel()." | ".$vehicle->getPlate());
                },
            ])
            ->add('operationType', EntityType::class, [
                'class' => \App\Entity\Vehicle\OperationType::class,
                'label' => 'Tipo de Operação',
                'choice_label' => 'name',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Preço',
            ])
            ->add('date', DateType::class, [
                'label' => 'Data',
                'widget' => 'single_text',
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notas',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
