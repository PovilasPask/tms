<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account_type', ChoiceType::class, [
                'choices'  => [
                    'Vartotojas' => 1,
                    'Teisėjas' => 2,
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Vardas Pavardė'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'El. Paštas',
                ]
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Nesutampa slaptažodžiai.',
                'options' => array('attr' => array('class' => 'form-control input-field-green')),
                'required' => true,
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Registruotis',
                'attr' => [
                    'class' => 'btn btn-secondary form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
