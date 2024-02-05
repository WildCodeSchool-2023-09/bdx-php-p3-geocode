<?php

namespace App\Form;

use App\Entity\Opened;
use App\Entity\Terminal;
use App\Entity\Town;
use LongitudeOne\Spatial\DBAL\Types\Geometry\PointType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerminalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('outletType', TextType::class, [
                'label' => 'Type de prise'
            ])
            ->add('numberOutlet', NumberType::class, [
                'label' => 'Nombre de prises'
            ])
            ->add('maxPower', NumberType::class, [
                'label' => 'Puissance maximum'
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
