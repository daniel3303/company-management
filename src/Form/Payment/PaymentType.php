<?php

namespace App\Form\Payment;

use App\Entity\Invoice\Invoice;
use App\Entity\Payment\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('method', TextType::class, [
                'label' => 'Método de Pagamento',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Valor',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descrição',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
