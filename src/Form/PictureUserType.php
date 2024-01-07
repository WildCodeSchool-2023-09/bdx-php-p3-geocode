<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PictureUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureFile', VichFileType::class, [
                'label' => false,
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_uri' => false, // not mandatory, default is true
                'delete_label' => 'Supprimer l\'image ?', // Personnalisez l'étiquette de suppression
//                'download_label' => 'Télécharger', // Personnalisez l'étiquette de téléchargement
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
