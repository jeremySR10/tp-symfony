<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label'=>"Nom de la formation"
            ])
            ->add('publishedAt', DateTimeType::class, [
                'label' => 'Date de publication',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('miniature', TextType::class, [
                'required' => false
            ])
            ->add('picture', TextType::class, [
                'required' => false
            ])
            ->add('videoId', TextType::class, [
                'label'=>"Id de la vidéo",
                'required' => false
            ])
            ->add('idNiveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'required' => true,
                'label'=>"Niveau de difficulté"
            ])
                
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
