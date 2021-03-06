<?php

namespace SW\DocManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadSessionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documents', 'collection', array(
                'type' => new DocumentType(true),
                'allow_add' => true,
                ))
            ->add('existedNames', 'hidden', array(
                'required'=> false
            ))
            ->add('weiter', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SW\DocManagerBundle\Entity\UploadSession'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sw_docmanagerbundle_uploadsession';
    }
}
