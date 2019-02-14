<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 9:02
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Serie;
use AppBundle\Entity\Acteur;
use AppBundle\Entity\Producteur;
use AppBundle\Entity\Resume;
use AppBundle\Entity\Image;
use AppBundle\Entity\Genre;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SerieadminController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/admin/addserie", name="adminaddserie")
     */

    public function addAction(Request $request){
        $serie = new serie();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $serie);

        $formBuilder
            ->add('Nom', TextType::class)
            ->add('Acteur', EntityType::class,[
            'class'=>'AppBundle:Acteur'
            ])
            ->add('Producteur', EntityType::class,[
                'class'=>'AppBundle:Producteur'
            ])
            ->add('Resume', EntityType::class,[
                'class'=>'AppBundle:Resume'
            ])
            ->add('Image', EntityType::class,[
                'class'=>'AppBundle:Image'
            ])
            ->add('Genre', EntityType::class,[
                'class'=>'AppBundle:Genre'
            ])
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($serie);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Série bien enregistrée.');

                return $this->redirectToRoute('admin');
            }
        }

        return $this->render('default/admin/serie/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editserie/{id}", name = "serieUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $serie = $em->getRepository('AppBundle:Serie')->find($id);

        if (!$serie) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour la serie numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $serie);

            $formBuilder
                ->add('Nom', TextType::class)
                ->add('Acteur', EntityType::class,[
                    'class'=>'AppBundle:Acteur'
                ])
                ->add('Producteur', EntityType::class,[
                    'class'=>'AppBundle:Producteur'
                ])
                ->add('Resume', EntityType::class,[
                    'class'=>'AppBundle:Resume'
                ])
                ->add('Image', EntityType::class,[
                    'class'=>'AppBundle:Image'
                ])
                ->add('Genre', EntityType::class,[
                    'class'=>'AppBundle:Genre'
                ])
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $genre = $serie->getGenres();
                $serie->removeGenre($genre[0]);
                $serie = $form->getData();
                $em->persist($serie);
                $em->flush();

                return $this->redirectToRoute('admin');
            }
        }

        return $this->render('default/admin/serie/update.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/admin/deleteserie/{id}", name = "serieDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $serie = $em->getRepository('AppBundle:Serie')->find($id);

        foreach ($serie->getGenres() as $genre)
            $serie->removeGenre($genre);

        $em->remove($serie);
        $em->flush();

        return $this->redirectToRoute('admin');
    }
}