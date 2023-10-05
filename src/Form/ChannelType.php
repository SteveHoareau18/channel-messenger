<?php

namespace App\Form;

use App\Entity\Channel;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'mapped'=>true,
                'label'=>'Nom',
                'attr'=>['class'=>'flex px-3 py-2 md:px-4 md:py-3 border-2 border-black rounded-lg font-medium placeholder:font-normal']
            ])
            ->add('caption', TextareaType::class, [
                'mapped'=>true,
                'label'=>'Description',
                'attr'=>['class'=>'input input-bordered w-full max-w-xs']
            ])
            ->add('type',  ChoiceType::class,
                [
                    'choices' => [
                        'Public' => Type::PUBLIC,
                        'Privé' => Type::PRIVATE,
                    ],
                    'expanded' => true,
                    'mapped'=>true
                ])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Channel::class,
        ]);
    }
}
