<?php

namespace App\Form;

use App\Entity\Acteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_prenom', TextType::class ,[
                'attr' => ['placeholder' => 'Nom et Prenom' , 'class' => 'form-control'],
                
            ])
            ->add('nationalite', TextType::class ,[
                'attr' => ['placeholder' => 'Nom et Prenom' , 'class' => 'form-control'],
                
            ])
            ->add('date_naissance', DateType::class ,[
                'attr' => ['placeholder' => 'Nom et Prenom' , 'class' => 'form-control'],
                
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Acteur::class,
        ]);
    }
}
