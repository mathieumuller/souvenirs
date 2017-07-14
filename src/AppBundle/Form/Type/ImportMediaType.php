<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportMediaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'album',
                EntityType::class,
                [
                    'class' => 'AppBundle:Album',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC')
                        ;
                    },
                    'choice_label' => 'name',
                    'label' => 'Ajouter dans un album?',
                    'attr' => [
                        'class' => 'selectpicker',
                        'data-live-search' => true,
                        'data-size' => '10',
                    ],
                    'preferred_choices' => function ($val, $key) {
                        return $val->getName() === 'Misc';
                    },
                    'required' => true,
                ]
            )
            ->add(
                'tags',
                EntityType::class,
                [
                    'class' => 'AppBundle:Tag',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.label', 'ASC')
                        ;
                    },
                    'multiple' => true,
                    'expanded' => false,
                    'choice_label' => 'label',
                    'label' => 'Ajouter des tags',
                    'placeholder' => 'Choisir un/des tag(s)',
                    'attr' => [
                        'class' => 'selectpicker',
                        'data-live-search' => true,
                        'data-size' => '10',
                    ],
                ]
            )
            ->add(
                'medias',
                Type\FileType::class,
                [
                    'label' => false,
                    'multiple' => true,
                ]
            )
            ->add(
                'submit',
                Type\SubmitType::class,
                [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => 'btn btn-success pull-right',
                    ],
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\\Model\\ImportMedias',
        ]);
    }
}
