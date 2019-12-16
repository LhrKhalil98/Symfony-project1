<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre' , TextType::class ,[
                'attr' => ['placeholder' => 'Titre' , 'class' => 'form-control'],
                
            ])
            ->add('date_sortie', DateType::class ,[
                'attr' => ['placeholder' => 'Date Sortie' , 'class' => 'form-control'],
                
            ])
            ->add('duree' , TextType::class ,[
                'attr' => ['placeholder' => 'Duree' , 'class' => 'form-control'],
                
            ])
            ->add('description' , TextType::class ,[
                'attr' => ['placeholder' => 'Descriptions' , 'class' => 'form-control'],
                
            ])
            ->add('video_url' , TextType::class ,[
                'attr' => ['placeholder' => 'Video Url ' , 'class' => 'form-control'],
                
            ])
            ->add('genre',EntityType::class , [
                'placeholder' => 'Genre ',
                'class' => 'App\Entity\Genre',
                'choice_label' => 'genre' ,
                'attr' => [  'class' => 'form-control']
            ])
            ->add('langue',EntityType::class , [
                'placeholder' => 'Langue ',
                'class' => 'App\Entity\Langue',
                'choice_label' => 'langue' ,
                'attr' => [  'class' => 'form-control']
            ])
            ->add('acteur' , EntityType::class, [
                'class' => 'App\Entity\Acteur',
                'choice_label' => 'nom_prenom',
                'label'        => 'Acteurs',
                'expanded'     => true,
                'multiple'     => true,
                'attr' => [  'class' => 'form-control']

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
