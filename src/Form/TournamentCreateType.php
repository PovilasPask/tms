<?php

namespace App\Form;

use App\Entity\Tournament;
use App\Entity\TournamentType;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournamentCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $doctrine = $options['doctrine'];
        $countryChoices = [];
        $countries = $doctrine->getRepository(Country::class)->findAll();
        foreach ($countries as $country){
            $countryChoices[$country->getName()] = $country;
        }

        $typeChoices = [];
        $types = $doctrine->getRepository(TournamentType::class)->findAll();
        foreach ($types as $type){
            $typeChoices[$type->getType()] = $type;
        }
        
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Turnyro pavadinimas'
                ]
            ])
            ->add('country', ChoiceType::class, [
                'choices'  => $countryChoices,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('region', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Miestas / rajonas / apskritis'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => $typeChoices,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('playersOnField', NumberType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Išsaugoti',
                'attr' => [
                    'class' => 'btn btn-secondary form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
        $resolver->setRequired('doctrine');
    }
}
