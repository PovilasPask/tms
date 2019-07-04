<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $doctrine = $options['doctrine'];
        $countryChoices = [];
        $countries = $doctrine->getRepository(Country::class)->findAll();
        foreach ($countries as $country){
            $countryChoices[$country->getName()] = $country;
        }
        
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Komandos pavadinimas'
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
                    'placeholder' => 'Miestas / kaimas, rajonas'
                ]
            ])
            ->add('venue', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Stadiono/aikštės pavadinimas ir adresas'
                ],
                'required' => false
            ])
            ->add('contacts', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Komandos kontaktai'
                ]
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
            'data_class' => Team::class,
        ]);
        $resolver->setRequired('doctrine');
    }
}
