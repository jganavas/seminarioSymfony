<?php

namespace App\Form;

use App\Entity\Administrator;
use App\Entity\User;
use App\Entity\Writer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-input'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Apellidos',
                'attr' => ['class' => 'form-input'],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biografía',
                'required' => false,
                'attr' => [
                    'class' => 'form-input',
                    'rows' => 5,
                    'placeholder' => 'Cuéntanos algo sobre ti...'
                ],
            ])
        ;

        // Add type-specific fields based on entity type
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if ($user instanceof Writer) {
                // Add specialty field for Writer
                $form->add('specialty', TextType::class, [
                    'label' => 'Especialidad',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-input',
                        'placeholder' => 'ej: Symfony, Frontend, DevOps...'
                    ],
                    'help' => 'Tu área de especialización o expertise principal'
                ]);
            } elseif ($user instanceof Administrator) {
                // Add permission level field for Administrator
                $form->add('permissionLevel', ChoiceType::class, [
                    'label' => 'Nivel de Permisos',
                    'choices' => [
                        'Nivel 1 - Administrador Básico' => 1,
                        'Nivel 2 - Moderador' => 2,
                        'Nivel 3 - Gestor de Contenido' => 3,
                        'Nivel 4 - Administrador de Sistema' => 4,
                        'Nivel 5 - Super Administrador' => 5,
                    ],
                    'attr' => ['class' => 'form-input'],
                    'help' => 'Define el nivel de permisos del administrador'
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
