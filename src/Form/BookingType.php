<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datetimeStart', DateTimeType::class, [
                'label' => 'Date et heure de la réservation',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer votre date de réservation.",
                    ]),
                ],
            ])
//            ->add('datetimeStart')
//            ->add('dateTimeEnd')
//            ->add('dateTimeEnd', TimeType::class, [
            ->add('dateTimeEnd', DateTimeType::class, [
                'label' => 'Fin de réservation',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer votre date de réservation.",
                    ]),
                ],
            ]);

//            ->add('terminal', EntityType::class, [
//                'class' => Terminal::class,
//                'choice_label' => 'id',
//            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
