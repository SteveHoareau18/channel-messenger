<?php

namespace App\Form;

use App\Entity\ChannelUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', TextType::class,[
                'mapped'=>false,
                'label'=>'Pseudo ou E-mail de l\'utilisateur'
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'INVITER'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChannelUser::class,
        ]);
    }
}
