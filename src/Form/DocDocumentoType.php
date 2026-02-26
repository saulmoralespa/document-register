<?php

namespace App\Form;

use App\Entity\DocDocumento;
use App\Entity\ProProceso;
use App\Entity\TipTipoDoc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocDocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Documento',
                'attr' => [
                    'placeholder' => 'Ej: INSTRUCTIVO DE DESARROLLO',
                    'maxlength' => 60,
                ],
                'constraints' => [
                    new NotBlank(message: 'El nombre es obligatorio'),
                    new Length(max: 60, maxMessage: 'El nombre no puede tener más de {{ limit }} caracteres'),
                ],
            ])
            ->add('tipo', EntityType::class, [
                'class' => TipTipoDoc::class,
                'choice_label' => 'nombre',
                'label' => 'Tipo de Documento',
                'placeholder' => '-- Seleccione un tipo --',
                'constraints' => [
                    new NotBlank(message: 'El tipo es obligatorio'),
                ],
            ])
            ->add('proceso', EntityType::class, [
                'class' => ProProceso::class,
                'choice_label' => 'nombre',
                'label' => 'Proceso',
                'placeholder' => '-- Seleccione un proceso --',
                'constraints' => [
                    new NotBlank(message: 'El proceso es obligatorio'),
                ],
            ])
            ->add('contenido', TextareaType::class, [
                'label' => 'Contenido del Documento',
                'attr' => [
                    'rows' => 10,
                    'placeholder' => 'Ingrese el contenido del documento...',
                    'maxlength' => 4000,
                ],
                'constraints' => [
                    new NotBlank(message: 'El contenido es obligatorio'),
                    new Length(max: 4000, maxMessage: 'El contenido no puede tener más de {{ limit }} caracteres'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DocDocumento::class,
        ]);
    }
}

