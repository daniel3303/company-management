<?php

namespace App\Form\Vehicle;

use App\Entity\Vehicle\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('model', TextType::class, [
                'label' => 'Modelo',
                'help' => 'Modelo do veículo, ex: Ford Transit'
            ])
            ->add('plate', TextType::class, [
                'label' => 'Matrícula',
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Ano',
                'help' => 'O ano de construção do veículo',
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notas (opcional)',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
