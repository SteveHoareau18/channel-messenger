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
                'label'=>'Pseudo ou E-mail de l\'utilisateur',
                'attr'=>['class'=>'flex px-3 py-2 md:px-4 md:py-3 border-2 border-black rounded-lg font-medium placeholder:font-normal',
                'list'=>'userList'],
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
