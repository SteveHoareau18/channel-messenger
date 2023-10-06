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
                'attr'=>['class'=>'flex px-3 py-2 md:px-4 md:py-3 border-2 border-black rounded-lg font-medium placeholder:font-normal']
            ])
            ->add('type',  ChoiceType::class,
                [
                    'choices' => [
                        'Public' => Type::PUBLIC,
                        'Privé' => Type::PRIVATE,
                    ],
                    'expanded' => true,
                    'mapped'=>true,
                    'choice_attr'=>['class'=>'m-2 text-sm font-medium'],
                    'label_attr'=>['class'=>'ml-2 text-sm font-medium text-gray-400'],
                    'row_attr'=>['class'=>'flex items-center mb-4'],
                ])
            ->add('submit',SubmitType::class, [
                'label'=>'ENREGISTRER'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Channel::class,
        ]);
    }
}
