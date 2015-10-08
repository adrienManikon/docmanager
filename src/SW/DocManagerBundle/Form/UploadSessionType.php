<?php

namespace SW\DocManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                'type' => new DocumentType(),
                'allow_add' => true,
                ))
            ->add('category', new CategoryType())
            ->add('subcategory1', new CategoryType())
            ->add('subcategory2', new CategoryType())
            ->add('subcategory3', new CategoryType())
            ->add('weiter', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
