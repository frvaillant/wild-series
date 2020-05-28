<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['help' => 'Saisissez votre titre (2 à 100 caractères'])
            ->add('summary', null, ['help' => 'Saisissez votre résumé (2 à 255 caractères'])
            ->add('poster', null, ['help' => 'Saisissez l\'url de votre image'])
            ->add('category', null, ['choice_label'=>'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
