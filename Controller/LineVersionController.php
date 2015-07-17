<?php

namespace Tisseo\PaonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tisseo\PaonBundle\Form\Type\LineVersionEditType;
use Tisseo\PaonBundle\Form\Type\LineVersionCreateType;
use Tisseo\PaonBundle\Form\Type\LineVersionCloseType;
use Tisseo\EndivBundle\Entity\LineVersion;

class LineVersionController extends AbstractController
{
    private function foundLineVersion(LineVersion $lineVersion = null)
    {
        if (empty($lineVersion))
        {
            $this->get('session')->getFlashBag()->add(
                'danger',
                $this->get('translator')->trans(
                    'line_version.not_found',
                    array(),
                    'default'
                )
            );

            return false;
        }

        return true;
    }

    /**
     * Edit
     * @param integer $lineVersionId
     *
     * Editing a LineVersion
     */
    public function editAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (!($this->foundLineVersion($lineVersion)))
        {
            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }

        // Update LineVersion -> LineVersionProperty -> Property relations
        $propertyManager = $this->get('tisseo_endiv.property_manager');
        $properties = $propertyManager->findAll();
        $lineVersion->synchronizeLineVersionProperties($properties);

        // Build the form and process its content
        $form = $this->createForm(
            new LineVersionEditType(),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_version_edit',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                ),
                'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
            )
        );

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $write = $lineVersionManager->save($form->getData());

            $this->get('session')->getFlashBag()->add(
                ($write[0] ? 'success' : 'danger'),
                $this->get('translator')->trans(
                    $write[1],
                    array(),
                    'default'
                )
            );

            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:edit.html.twig',
            array(
                'form' => $form->createView(),
                'lineVersion' => $lineVersion
            )
        );
    }

    /**
     * Close LineVersion
     * @param integer $lineVersionId
     *
     * Closing a LineVersion by setting its endDate.
     */
    public function closeAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        $lineVersion = $lineVersionManager->find($lineVersionId);

        if (!($this->foundLineVersion($lineVersion)))
        {
            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }

        // Build the form and process its content
        $form = $this->createForm(
            new LineVersionCloseType(),
            $lineVersion,
            array(
                'action' => $this->generateUrl(
                    'tisseo_paon_line_version_close',
                    array(
                        'lineVersionId' => $lineVersion->getId()
                    )
                )
            )
        );

        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $write = $lineVersionManager->save($form->getData());

            $this->get('session')->getFlashBag()->add(
                ($write[0] ? 'success' : 'danger'),
                $this->get('translator')->trans(
                    $write[1],
                    array(),
                    'default'
                )
            );

            return $this->redirect(
                $this->generateUrl('tisseo_paon_line_version_list')
            );
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:close.html.twig',
            array(
                'form' => $form->createView(),
                'lineVersion' => $lineVersion
            )
        );
    }

    /**
     * Create
     * @param integer $lineId
     *
     * Creating a new LineVersion
     */
    public function createAction(Request $request, $lineId = null)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        if ($lineId === null)
            $lineId = $request->request->get('lineId');

        $lineManager = $this->get('tisseo_endiv.line_manager');

        $minDate = null;
        if (!empty($lineId))
        {
            $propertyManager = $this->get('tisseo_endiv.property_manager');
            $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

            $properties = $propertyManager->findAll();
            $lineVersionResult = $lineVersionManager->findLastLineVersionOfLine($lineId);

            // no previous offer on this line
            if (empty($lineVersionResult))
            {
                $line = $lineManager->find($lineId);
                $lineVersion = new LineVersion($properties, null, $line);
            }
            else
            {
                $lineVersion = new LineVersion($properties, $lineVersionResult, null);
                $minDate = $lineVersionResult->getStartDate();
                $minDate->add(new \DateInterval('P1D'));
            }

            $modificationManager = $this->get('tisseo_endiv.modification_manager');
            $form = $this->createForm(
                new LineVersionCreateType($modificationManager, ($lineVersion->getLine() !== null ? $lineVersion->getLine()->getId() : null)),
                $lineVersion,
                array(
                    'action' => $this->generateUrl(
                        'tisseo_paon_line_version_create',
                        array(
                            'lineId' => $lineVersion->getLine()->getId()
                        )
                    ),
                    'em' => $this->getDoctrine()->getManager($this->container->getParameter('endiv_database_connection'))
                )
            );

            $form->handleRequest($request);
            if ($form->isValid()) {

                $user = $this->get('security.context')->getToken()->getUser();
                $write = $lineVersionManager->create($form->getData(), $user->getUsername());

                $this->get('session')->getFlashBag()->add(
                    ($write[0] ? 'success' : 'danger'),
                    $this->get('translator')->trans(
                        $write[1],
                        array(),
                        'default'
                    )
                );

                return $this->redirect(
                    $this->generateUrl('tisseo_paon_line_version_list')
                );
            }

            return $this->render(
                'TisseoPaonBundle:LineVersion:create.html.twig',
                array(
                    'form' => $form->createView(),
                    'lineVersion' => $lineVersion,
                    'lines' => $lineManager->findAllLinesByPriority(),
                    'minDate' => $minDate
                )
            );
        }

        return $this->render(
            'TisseoPaonBundle:LineVersion:create.html.twig',
            array(
                'form' => null,
                'lineVersion' => null,
                'lines' => $lineManager->findAllLinesByPriority()
            )
        );
    }

    /**
     * List
     *
     * Listing current/future versions of LineVersions.
     */
    public function listAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');

        return $this->render(
            'TisseoPaonBundle:LineVersion:list.html.twig',
            array(
                'pageTitle' => 'menu.line_version_active',
                'data' => $this->get('tisseo_endiv.line_version_manager')->findActiveLineVersions(new \Datetime(), null, true)
            )
        );
    }

    /**
     * Show
     * @param integer $lineVersionId
     *
     * Display a LineVersion in a view.
     */
    public function showAction(Request $request, $lineVersionId)
    {
        $this->isGranted(
            array(
                'BUSINESS_MANAGE_LINE_VERSION',
                'BUSINESS_LIST_LINE_VERSION'
            )
        );

        $history = false;
        $title = 'line_version.show';

        if ($request->isXmlHttpRequest())
        {
            $history = $request->get('history');
            if ($history)
                $title = 'line_version.history.show';
        }

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');
        return $this->render(
            'TisseoPaonBundle:LineVersion:show.html.twig',
            array(
                'title' => $title,
                'history' => $history,
                'lineVersion' => $lineVersionManager->find($lineVersionId)
            )
        );
    }

    /**
     * History
     *
     * Listing all previous versions of LineVersions.
     */
    public function historyAction()
    {
        $this->isGranted('BUSINESS_LIST_LINE_VERSION');

        $lineManager = $this->get('tisseo_endiv.line_manager');

        return $this->render(
            'TisseoPaonBundle:LineVersion:history.html.twig',
            array(
                'pageTitle' => 'menu.line_version_history',
                'lines' => $lineManager->findAllLinesByPriority()
            )
        );
    }

    /**
     * Clean
     * @param integer $lineVersionId
     *
     * Cleaning a LineVersion's timetable data from database.
     */
    public function cleanAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $storedProcedureManager = $this->get('tisseo_endiv.stored_procedure_manager');
        $result = $storedProcedureManager->cleanLineVersion($lineVersionId);

        $this->get('session')->getFlashBag()->add(
            ($result ? 'success' : 'danger'),
            $this->get('translator')->trans(
                ($result ? 'line_version.clean' : 'line_version_not_clean'),
                array(),
                'default'
            )
        );

        return $this->redirect(
            $this->generateUrl('tisseo_paon_line_version_list')
        );
    }

    /**
     * Delete
     * @param integer $lineVersionId
     *
     * Deleting a LineVersion from database
     */
    public function deleteAction($lineVersionId)
    {
        $this->isGranted('BUSINESS_MANAGE_LINE_VERSION');

        $lineVersionManager = $this->get('tisseo_endiv.line_version_manager');

        try {
            $lineVersionManager->delete($lineVersionId);

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'line_version.deleted', array(), 'default'
                )
            );
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('tisseo_paon_line_version_list')
        );
    }
}
