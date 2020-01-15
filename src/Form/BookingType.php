<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Offer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('compagny', TextType::class, [
                'label' => 'Nom de votre société',
                'required' => true
            ])
            ->add('phonenumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => true
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'required' => true
            ])
            ->add('offer', EntityType::class, [
                // je sélectionne l'entité à afficher, ici
                // category car ma relation fait référence aux offres
                'class' => Offer::class,
                // je choisi la propriété de offer qui s'affiche
                // dans le select du html
                'label' => 'Formule séléctionnée',
                'choice_label'=>function (Offer $offer)
                {
                    return $offer->getName();
                },
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réserver'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
