<?php

namespace App\Form\Invoice;

use App\Entity\Company\Company;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Entity\Payment\Payment;
use App\Form\Payment\PaymentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Número da fatura',
            ])
            ->add('date', DateType::class, [
                'label' => 'Data',
                'widget' => 'single_text',
            ])
            ->add('issuer', EntityType::class, [
                'class' => Company::class,
                'label' => 'Emissor',
                'choice_label' => 'name',
            ])
            ->add('client', EntityType::class, [
                'class' => Company::class,
                'label' => 'Cliente',
                'choice_label' => 'name',
            ])
            ->add('items', CollectionType::class, [
                'label' => 'Items',
                'entry_type' => ItemType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
            ->add('payments', CollectionType::class, [
                'label' => 'Pagamentos',
                'entry_type' => PaymentType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'delete_empty' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
