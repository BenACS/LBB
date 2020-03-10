<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class RegistrationTypeTwoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('birthdate', BirthdayType::class)
            ->add('newsletter')
            ->add('accountType', ChoiceType::class, [
                'choices' => [
                    'Individual' => 'individual',
                    'Company' => 'company'
                ]
            ])
            ->add('siret')
            ->add('codeTva')
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'method' => 'POST'
        ]);
    }
}
