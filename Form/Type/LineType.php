<?php

namespace Tisseo\DatawarehouseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\DatawarehouseBundle\Form\Type\LineDatasourceType;

class LineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'number',
            'text',
            array('label' => 'line.labels.number')
        );
        $builder->add(
            'physicalMode',
            'entity',
            array(
                'class' => 'TisseoDatawarehouseBundle:PhysicalMode',
                'property' => 'name',
                'label' => 'line.labels.physical_mode'
            )
        );
        $builder->add(
            'lineDatasources',
            'collection',
            array(
                'type' => new LineDatasourceType(),
                'allow_add' => true,
                'by_reference' => false
            )
        );
        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\DatawarehouseBundle\Entity\Line'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datawarehouse_line';
    }
}
