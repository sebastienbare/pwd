<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 10/01/19
 * Time: 9:14
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Genre;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends \AppBundle\Controller\DefaultController{

    /**
     * @Route("/admin/addgenre", name="addgenre")
     */

    public function addAction(Request $request){
        $genre = new Genre();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $genre);

        $formBuilder
            ->add('Genre', TextType::class)
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($genre);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Genre bien enregistrée.');

                return $this->redirectToRoute('admin/genre');
            }
        }

        return $this->render('default//admin/genre/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editgenre/{id}", name = "genreUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository('AppBundle:Genre')->find($id);

        if (!$genre) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour l\'genre numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $genre);

            $formBuilder
                ->add('Genre', TextType::class)
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $genre = $form->getData();
                $em->persist($genre);
                $em->flush();

                return $this->redirectToRoute('admin/genre');
            }
        }

        return $this->render('default/admin/genre/update.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/admin/deletegenre/{id}", name = "genreDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $genre = $em->getRepository('AppBundle:Genre')->find($id);

        $em->remove($genre);
        $em->flush();

        return $this->redirectToRoute('admin/genre');
    }
}