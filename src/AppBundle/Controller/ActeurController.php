<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 8:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Acteur;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActeurController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/admin/addacteur", name="addacteur")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function addAction(Request $request){
        $acteur = new acteur();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $acteur);

        $formBuilder
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($acteur);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Acteur bien enregistrée.');

                return $this->redirectToRoute('admin/acteur');
            }
        }

        return $this->render('default/admin/acteur/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editacteur/{id}", name = "acteurUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $acteur = $em->getRepository('AppBundle:Acteur')->find($id);

        if (!$acteur) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour l\'acteur numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $acteur);

            $formBuilder
                ->add('Nom', TextType::class)
                ->add('Prenom', TextType::class)
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $acteur = $form->getData();
                    $em->persist($acteur);
                    $em->flush();

                    return $this->redirectToRoute('admin/acteur');
                }
            }

            return $this->render('default/admin/acteur/update.html.twig', array('form' => $form->createView(),));
        }

    /**
     * @Route("/admin/deleteacteur/{id}", name = "acteurDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $acteur = $em->getRepository('AppBundle:Acteur')->find($id);

        $em->remove($acteur);
        $em->flush();

        return $this->redirectToRoute('admin/acteur');
    }

}