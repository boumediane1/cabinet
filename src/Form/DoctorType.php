<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\Speciality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('name', TextType::class)
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => $options['is_edit'] === false,
            ])
            ->add('speciality', EntityType::class, [
                'class' => Speciality::class,
                'choice_label' => 'title',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
            'is_edit' => false,
        ]);
    }
}
