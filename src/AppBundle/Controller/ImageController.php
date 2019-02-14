<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 9:00
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\FileUploader;

class ImageController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/admin/addimage", name="addimage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request){
        $image = new Image();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $image);

        $formBuilder
            ->add('Source', TextType::class)
            ->add('Alt', TextType::class)
            ->add('Image', FileType::class)
            ->add('Envoyer', SubmitType::class);

        $form = $formBuilder->getForm();


        if($request->isMethod('POST')){

            $form->handleRequest($request);

            if($form->isValid()){
                $upload = new FileUploader();
                $file = $image->getImage();
                $fileName = $upload->upload($file,$image);

                $image->setSource($fileName);

                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'image bien enregistrée.');

                return $this->redirectToRoute('admin/image');
            }
        }

        return $this->render('default//admin/image/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/admin/editimage/{id}", name = "imageUpdate")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($id);

        if (!$image) {
            throw $this->createNotFoundException(
                'Aucun id trouver pour l\'image numero  ' . $id
            );
        } else {

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $image);

            $formBuilder
                ->add('Source', TextType::class)
                ->add('Alt', TextType::class)
                ->add('Image', FileType::class)
                ->add('Modifier', SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $upload = new FileUploader();
                $file = $image->getImage();
                $fileName = $upload->upload($file,$image);

                $image->setSource($fileName);

                $em->persist($image);
                $em->flush();

                return $this->redirectToRoute('admin/image');
            }
        }

        return $this->render('default/admin/image/update.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/admin/deleteimage/{id}", name = "imageDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($id);

        $em->remove($image);
        $em->flush();

        return $this->redirectToRoute('admin/image');
    }
}