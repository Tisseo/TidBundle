<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Doctrine\ORM\EntityRepository;
use Tisseo\TidBundle\Form\DataTransformer\EntityToIntTransformer;
use Tisseo\EndivBundle\Services\ModificationManager;

class LineVersionCreateType extends AbstractType
{
    private $schematicTransformer = null;
    private $lineTransformer = null;
    private $modificationManager = null;
    private $lineId = null;

    public function __construct(ModificationManager $modificationManager, $lineId)
    {
        $this->modificationManager = $modificationManager;
        $this->lineId = $lineId;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        usort($view->children['childLine']->vars['choices'], function(ChoiceView $choice1, ChoiceView $choice2) {
            $lineVersion1 = $choice1->data;
            $lineVersion2 = $choice2->data;
            if ($lineVersion1->getLine()->getPriority() == $lineVersion2->getLine()->getPriority())
                return strnatcmp($lineVersion1->getLine()->getNumber(), $lineVersion2->getLine()->getNumber());
            if ($lineVersion1->getLine()->getPriority() > $lineVersion2->getLine()->getPriority())
                return 1;
            if ($lineVersion1->getLine()->getPriority() < $lineVersion2->getLine()->getPriority())
                return -1;
        });
    }

    private function buildTransformers($em)
    {
        $this->schematicTransformer = new EntityToIntTransformer($em);
        $this->schematicTransformer->setEntityClass("Tisseo\\EndivBundle\\Entity\\Schematic");
        $this->schematicTransformer->setEntityRepository("TisseoEndivBundle:Schematic");
        $this->schematicTransformer->setEntityType("schematic");

        $this->lineTransformer = new EntityToIntTransformer($em);
        $this->lineTransformer->setEntityClass("Tisseo\\EndivBundle\\Entity\\Line");
        $this->lineTransformer->setEntityRepository("TisseoEndivBundle:Line");
        $this->lineTransformer->setEntityType("line");
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransformers($options['em']);

        $builder
            ->add(
                $builder->create(
                    'line',
                    'hidden'
                )->addModelTransformer($this->lineTransformer)
            )
            ->add(
                'startDate',
                'datepicker_tid',
                array(
                    'label' => 'line_version.labels.start_date',
                    'attr' => array(
                        'data-from-date' => false
                    )
                )
            )
            ->add(
                'plannedEndDate',
                'datepicker_tid',
                array(
                    'label' => 'line_version.labels.planned_end_date',
                    'attr' => array(
                        'data-to-date' => true
                    )
                )
            )
            ->add(
                'version',
                'integer',
                array(
                    'label' => 'line_version.labels.version',
                    'precision' => 0,
                    'read_only' => true
                )
            )
            ->add(
                'childLine',
                'entity',
                array(
                    'label' => 'line_version.labels.child_line',
                    'class' => 'TisseoEndivBundle:LineVersion',
                    'property' => 'line.number',
                    'empty_value' => '',
                    'required' => false,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('lv')
                        ->join('lv.line', 'l')
                        ->where('lv.startDate <= CURRENT_DATE()')
                        ->andWhere('lv.endDate is null OR lv.endDate > CURRENT_DATE()')
                        ->orderBy('l.number', 'ASC');
                    }
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'label' => 'line_version.labels.name'
                )
            )
            ->add(
                'forwardDirection',
                'text',
                array(
                    'label' => 'line_version.labels.forward'
                )
            )
            ->add(
                'backwardDirection',
                'text',
                array(
                    'label' => 'line_version.labels.backward'
                )
            )
            ->add(
                'fgColor',
                'entity',
                array(
                    'label' => 'line_version.labels.fg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where("c.name IN ('Blanc','Noir')");
                    }
                )
            )
            ->add(
                'bgColor',
                'entity',
                array(
                    'label' => 'line_version.labels.bg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true
                )
            )
            ->add(
                'Property',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Property',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'line_version.labels.properties'
                )
            )
            ->add(
                'depot',
                'text',
                array(
                    'label' => 'line_version.labels.depot'
                )
            )
            ->add(
                'button_schematic',
                'button',
                array(
                    'label' => 'line_version.labels.choose_schematic',
                    'attr' => array(
                        'class' => 'choose-schematic'
                    )
                )
            )

            /*
             * This field is mandatory but must be hidden
             * it will be hiddent and positioned with css for allow use html5 validation
             * See classes specified into attribute : class
             * TODO :  This system will be removed
             */
            ->add(
                $builder->create(
                    'schematic',
                    'hidden',
                    array(
                        'attr' => array(
                            'class' => 'input-hidden schematic-min-width-field'
                        ),
                    )
                )->addModelTransformer($this->schematicTransformer)
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'line_version.labels.comment',
                    'required' => false
                )
            )
            ->add(
                'ResolvedModifications',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Modification',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'line_version.labels.resolved_modifications',
                    'query_builder' => $this->modificationManager->findAllNotResolvedByLine($this->lineId)
                )
            )
            ->setAction($options['action'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineVersion',
                'validation_groups' => array('LineVersion', 'registration')
            )
        );

        $resolver->setRequired(array(
            'em'
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_line_version';
    }
}