<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [new Length(['min' => 3])],
                'invalid_message' => 'test'
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
            ])
            ->add('confirm_password', PasswordType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3])],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'invalid_message' => 'test'
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
