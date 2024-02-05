<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameFields($builder);
        $this->addBirthdayField($builder);
        $this->addEmailField($builder);
        $this->addGenderField($builder);
        $this->addAgreeTermsField($builder);
        $this->addPasswordField($builder);
        $builder
            ->add('town', TownNameAutocompleteField::class, [
                'class' => Town::class,
                'label' => ' Ville',
                'attr' => [
                    'class' => 'input'
                ],
            ]);
    }
    private function addNameFields(FormBuilderInterface $builder): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères",
                        'maxMessage' => "Veuillez choisir un nom d'utilisateur entre 2 et 255 caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z]+$/i',
                        'message' => "Nom d'utilisateur invalide",
                        'htmlPattern' => '^[a-zA-Z]+$',
                    ]),
                    new NotBlank([
                        'message' => "Veuillez entrer votre nom.",
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => "Veuillez choisir un prénom d'utilisateur entre 2 et 255 caractères",
                        'maxMessage' => "Veuillez choisir un prénom d'utilisateur entre 2 et 255 caractères.",
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z]+$/i',
                        'message' => "Prénom d'utilisateur invalide",
                        'htmlPattern' => '^[a-zA-Z]+$',
                    ]),
                    new NotBlank([
                        'message' => "Veuillez entrer votre prénom.",
                    ]),
                ],
            ])
        ;
    }
    private function addBirthdayField(FormBuilderInterface $builder): void
    {
        $builder
            ->add('birthday', DateType::class, [
                'label' => 'Anniversaire',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer votre date d'anniversaire.",
                    ]),
                    new LessThan([
                        'value' => 'now',
                        'message' => "La date d'anniversaire doit être antérieure à aujourd'hui.",
                    ]),
                    new LessThanOrEqual([
                        'value' => '-18 years',
                        'message' => "Vous devez avoir au moins 18 ans.",
                    ]),
                ],
            ])
        ;
    }
    private function addEmailField(FormBuilderInterface $builder): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer votre e-mail.",
                    ]),
                ]
            ])
        ;
    }
    private function addGenderField(FormBuilderInterface $builder): void
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'Femme',
                    'Non binaire' => 'non_binaire',
                    'Ne pas spécifier' => 'non_spécifier',
                ],
                'expanded' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez remplir ce champ.",
                    ]),
                ]
            ])
        ;
    }

    private function addAgreeTermsField(FormBuilderInterface $builder): void
    {
        $builder
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'En m\'inscrivant à ce site j\'accepte toutes les conditions  ',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'row_attr' => [
                    'class' => 'liste'
                ],
                'attr' => [
                    'class' => 'input[type="checkbox"]'
                ],
            ])
        ;
    }

    private function addPasswordField(FormBuilderInterface $builder): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
