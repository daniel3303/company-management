<?php

namespace App\Form\Invoice;

use App\Entity\Invoice\Item;
use App\Entity\Product\Product;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Quantidade',
            ])
            ->add('pricePerUnit', NumberType::class, [
                'label' => 'Preço por unidade',
            ])
            ->add('waste', NumberType::class, [
                'label' => 'Refúgo',
            ])
            ->add('product', EntityType::class, [
                'label' => 'Produto',
                'class' => Product::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
