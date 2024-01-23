<?php

namespace App\Form;

use App\Entity\Opened;
use App\Entity\Terminal;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerminalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('point')
            ->add('address')
            ->add('outletType')
            ->add('numberOutlet')
            ->add('maxPower')
            ->add('town', EntityType::class, [
                'class' => Town::class,
        'choice_label' => 'id',
            ])
            ->add('opened', EntityType::class, [
                'class' => Opened::class,
        'choice_label' => 'id',
        'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terminal::class,
        ]);
    }
}
