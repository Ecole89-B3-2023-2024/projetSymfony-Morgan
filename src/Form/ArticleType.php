<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu')
            ->add('date_publication')
            ->add('auteur', EntityType::class, [
                'class'         => User::class,
                'choice_label'  => 'nom'
            ])
            ->add('themes', EntityType::class, [
                'class'         => Theme::class,
                'choice_label'  => 'nom',
                'label'         => 'Choisissez un ou plusieurs thèmes',
                'multiple'      => true,
                'expanded'      => true
            ])
            ->add('file', FileType::class, [
                'label'         => 'Photo (png, jpeg)',
                'data_class'    => null
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
