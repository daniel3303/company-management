<?php

namespace App\Form\Company;

use App\Entity\Company\Company;
use App\Repository\Company\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class CheckingAccountType extends AbstractType {
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository) {
        $this->companyRepository = $companyRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('client', EntityType::class, [
                'label' => 'Nome',
                'choices' => $options['clients'] ?? $this->companyRepository->findAll(),
                'choice_label' => 'name',
                'required' => true,
                'class' => Company::class,
                'constraints' => [
                    new NotNull(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('clients');
        $resolver->setDefault('clients', null);
    }
}
