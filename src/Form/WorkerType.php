<?php

namespace App\Form;

use App\Entity\DepartmentEntity;
use App\Entity\WorkerEntity;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Imię'])
            ->add('lastName', TextType::class, ['label' => 'Nazwisko'])
            ->add('salary', NumberType::class, ['label' => 'Wynagrodzenie'])
            ->add('dateEmployment', DateType::class, [
                'label' => 'Data rozpoczęcia pracy',
                'widget' => 'choice',
            ])
            ->add('department', EntityType::class, [
                // looks for choices from this entity
                'class' => DepartmentEntity::class,
                'label' => 'Dział',
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Nowy Pracownik'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkerEntity::class,
        ]);
    }
}
