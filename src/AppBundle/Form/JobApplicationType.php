<?php

namespace AppBundle\Form;

use AppBundle\Entity\JobApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('candidateFullName', TextType::class)
            ->add('candidateEmailAddress', EmailType::class)
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '15',
                ],
            ])
            ->add('resume', FileType::class, [
                'label' => 'Upload a resume',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobApplication::class,
            'intention' => 'job_application_form',
        ]);
    }
}
