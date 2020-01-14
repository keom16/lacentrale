<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la formule',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'required' => true
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Tarif'
            ])
            ->add('coffee', CheckboxType::class, [
                'label' => 'Café',
                'required' => false
            ])
            ->add('printer', CheckboxType::class, [
                'label' => 'Imprimante',
                'required' => false
            ])
            ->add('wireless', CheckboxType::class, [
                'label' => 'Wifi',
                'required' => false
            ])
            ->add('locker', CheckboxType::class, [
                'label' => 'Casier',
                'required' => false
            ])
            ->add('assigned_office', CheckboxType::class, [
                'label' => 'Bureau assigné',
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
            'data_class' => Offer::class,
        ]);
    }
}
