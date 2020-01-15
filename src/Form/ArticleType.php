<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => true
            ])
            ->add('images', FileType::class, [
                'label' => 'Image associée',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
            ])
            // Je créé un nouveau champs de formulaire ce champs est pour la propriété 'catégorie'
            // vu que ce champs contient une relation vers une autre entité, le type choisi doit être EntityType
            ->add('category', EntityType::class, [
                // je sélectionne l'entité à afficher, ici
                // category car ma relation fait référence aux catégories
                'class' => Category::class,
                // je choisi la propriété de catégorie qui s'affiche
                // dans le select du html
                'label' => 'Catégorie',
                'choice_label'=>function (Category $category)
                {
                    return $category->getName();
                },
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
