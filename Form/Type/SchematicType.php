<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SchematicType extends AbstractType
{
    protected $isBatch;

    protected $addInfo;

    protected $groupGis;

    /**
     * @param bool $isBatch
     */
    public function __construct($isBatch = false, $addInfo = false, $groupGis = false)
    {
        $this->isBatch = $isBatch;
        $this->addInfo = $addInfo;
        $this->groupGis = $groupGis;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->isBatch)
        {
            $builder->add(
                'deprecated',
                'checkbox',
                array(
                    'label' => false,
                    'required' => false
                )
            );
        }
        else if ($this->groupGis)
        {
            $builder->add(
                'groupGis',
                'checkbox',
                array(
                    'label' => false,
                    'required' => false
                )
            );
        }
        else
        {
            $builder
                ->add(
                    'name',
                    'hidden',
                    array(
                        'label' => 'tisseo.paon.schematic.label.name'
                    )
                )
                ->add(
                    'comment',
                    'textarea',
                    array(
                        'label' => 'tisseo.paon.schematic.label.comment'
                    )
                )
                ->add(
                    'deprecated',
                    'hidden',
                    array(
                        'data' => 0
                    )
                )
            ;
            if ($this->addInfo)
            {
                $builder
                    ->add(
                        'date',
                        'date',
                        array(
                            'label' => 'tisseo.paon.schematic.label.date',
                            'widget' => 'single_text',
                            'read_only' => true,
                            'format' => 'dd/MM/yyyy'
                        )
                    )
                ;
            }
            else
            {
                $builder
                    ->add(
                        'date',
                        'tisseo_datepicker',
                        array(
                            'label' => 'tisseo.paon.schematic.label.date',
                            'attr' => array(
                                'class' => 'input-date'
                            )
                        )
                    )
                    ->add(
                        'file',
                        'file',
                        array(
                            'label' => 'tisseo.paon.schematic.label.file',
                            'required' => true
                        )
                    )
                ;
            }
            $builder->setAction($options['action']);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\Schematic',
                'attr' => array(
                    'enctype' => 'multipart/form-data'
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_schematic';
    }
}