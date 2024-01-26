<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse e-mail',
                'attr' => [
                    'class' => 'input'
                ],
            ])
            ->add('demande', ChoiceType::class, [
                'label' => 'Demande',
                'choices' => [
                    'Demande d\'informations' => 'Informations',
                    'Demande de partenariats' => 'Partenariats',
                    'Autres Demandes' => 'Autres'
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'input'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Merci de remplir le formulaire avant d\'envoyer']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le message doit contenir max 255 caractères'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
