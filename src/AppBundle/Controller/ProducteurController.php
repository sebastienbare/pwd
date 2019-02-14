<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 9:00
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Producteur;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProducteurController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/admin/addproducteur", name="addproducteur")
     */

    public function addAction(Request $request){
        $producteur = new Producteur();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $producteur);

        $formBuilder
            ->add('Nom', TextType::class)
            ->add('Prenom', TextType::class)
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($producteur);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'producteur bien enregistrée.');

                return $this->redirectToRoute('admin/producteur');
            }
        }

        return $this->render('default/admin/producteur/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editproducteur/{id}", name = "producteurUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $producteur = $em->getRepository('AppBundle:Producteur')->find($id);

        if (!$producteur) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour l\'producteur numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $producteur);

            $formBuilder
                ->add('Nom', TextType::class)
                ->add('Prenom', TextType::class)
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $producteur = $form->getData();
                $em->persist($producteur);
                $em->flush();

                return $this->redirectToRoute('admin/producteur');
            }
        }

        return $this->render('default/admin/producteur/update.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/admin/deleteproducteur/{id}", name = "producteurDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $producteur = $em->getRepository('AppBundle:Producteur')->find($id);

        $em->remove($producteur);
        $em->flush();

        return $this->redirectToRoute('admin/producteur');
    }
}