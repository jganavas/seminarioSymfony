<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder' => 'Ej: Garfield',
                    'class' => 'input-field'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Por favor ingresa tu nombre',
                    ),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'El nombre debe tener al menos {{ limit }} caracteres',
                        maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres',
                    ),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Apellidos',
                'attr' => [
                    'placeholder' => 'Ej: Gato Naranja',
                    'class' => 'input-field'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Por favor ingresa tus apellidos',
                    ),
                    new Length(
                        min: 2,
                        max: 100,
                        minMessage: 'Los apellidos deben tener al menos {{ limit }} caracteres',
                        maxMessage: 'Los apellidos no pueden tener más de {{ limit }} caracteres',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'tu@email.com',
                    'class' => 'input-field'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Por favor ingresa tu email',
                    ),
                    new Email(
                        message: 'Por favor ingresa un email válido',
                    ),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Acepto los términos y condiciones',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'Debes aceptar los términos y condiciones',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Contraseña',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mínimo 8 caracteres',
                    'class' => 'input-field'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Por favor ingresa una contraseña',
                    ),
                    new Length(
                        min: 8,
                        max: 4096,
                        minMessage: 'La contraseña debe tener al menos {{ limit }} caracteres',
                    ),
                    new Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                        message: 'La contraseña debe contener al menos una mayúscula, una minúscula y un número',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
