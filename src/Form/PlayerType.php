<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $year = date("Y");
        $month = 1;
        $day = 1;
        if ($entity->getBDate() !== null) {
            $year = $entity->getBDate()->format("Y");
            $month = $entity->getBDate()->format("n");
            $day = $entity->getBDate()->format("j");
        }

        $years = [];
        $months = [];
        $days = [];
        for ($i=0; $i <= 100; $i++) { 
            $d = date("Y") - $i;
            $years[$d] = $d;
        }
        for ($i=1; $i <= 12; $i++) { 
            $months[$i] = $i;
        }
        for ($i=1; $i <= 31; $i++) { 
            $days[$i] = $i;
        }
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green',
                    'placeholder' => 'Žaidėjo vardas ir pavardė'
                ]
            ])
            ->add('year', ChoiceType::class, [
                'choices'  => $years,
                'data' => $year,
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false
            ])
            ->add('month', ChoiceType::class, [
                'choices'  => $months,
                'data' => $month,
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false
            ])
            ->add('day', ChoiceType::class, [
                'choices'  => $days,
                'data' => $day,
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false
            ])
            ->add('number', NumberType::class, [
                'attr' => [
                    'class' => 'form-control input-field-green'
                ],
                'required' => false
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
            'data_class' => Player::class,
        ]);
    }
}
