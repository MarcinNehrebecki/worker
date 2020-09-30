<?php

namespace App\Form;

use App\Entity\DepartmentEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('bonusPrice', NumberType::class, ['label' => 'Premia Kwotowo/Procenowo'])
            ->add('type', ChoiceType::class, [
                'label' => 'Kwota/Procent',
                'choices' => [
                    'Kwota' => DepartmentEntity::FIELD_SALARY,
                    'Procent' => DepartmentEntity::FIELD_PERCENT,
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Nowy dział'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DepartmentEntity::class,
        ]);
    }
}
