<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 9:00
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Resume;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ResumeController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/admin/addresume", name="addresume")
     */

    public function addAction(Request $request){
        $resume = new Resume();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $resume);

        $formBuilder
            ->add('Resume', TextareaType::class)
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($resume);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Resumé bien enregistrée.');

                return $this->redirectToRoute('admin/resume');
            }
        }

        return $this->render('default/admin/resume/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editresume/{id}", name = "resumeUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository('AppBundle:Resume')->find($id);

        if (!$resume) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour l\'resume numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $resume);

            $formBuilder
                ->add('Resume', TextareaType::class)
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $resume = $form->getData();
                $em->persist($resume);
                $em->flush();

                return $this->redirectToRoute('admin/resume');
            }
        }

        return $this->render('default/admin/resume/update.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/admin/deleteresume/{id}", name = "resumeDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $resume = $em->getRepository('AppBundle:Resume')->find($id);

        $em->remove($resume);
        $em->flush();

        return $this->redirectToRoute('admin/resume');
    }
}