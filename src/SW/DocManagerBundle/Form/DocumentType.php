<?php

namespace SW\DocManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                'required'=> false
            ))
            ->add('path', 'hidden', array(
                'required'=> false
            ))
            ->add('name', 'text', array(
                'attr'=> array('placeholder'=>'Dateiname')
            ))
            ->add($builder->create('date', 'text', array(
                'attr'=> array('placeholder'=>'Initialen')
            ))
                ->addViewTransformer(new DateTimeToStringTransformer(null, null, 'd.m.Y')))                
            ->add('initials', 'text', array(
                'required'=> false,
                'attr'=> array('readonly'=>'readonly')
            ))
            ->add('creator', new UserType(), array(
                'attr'=> array('readonly'=>'readonly')
            ))
            ->add('file', 'file', array(
                'attr' => array('class' => 'button full-size bg-gray fg-white')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SW\DocManagerBundle\Entity\Document'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'sw_docmanagerbundle_document';
    }
}
