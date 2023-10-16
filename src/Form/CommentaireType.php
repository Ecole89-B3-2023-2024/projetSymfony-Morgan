<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
            ->add('date_publication')
            ->add('auteur', EntityType::class, [
                'class'         => User::class,
                'choice_label'  => 'nom',
            ])
            ->add('article', EntityType::class, [
                'class'         => Article::class,
                'choice_label'  => 'titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
