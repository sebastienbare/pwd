<?php
/**
 * Created by PhpStorm.
 * User: sebastienbare
 * Date: 17/01/19
 * Time: 9:02
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Vote;
use AppBundle\Entity\Serie;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends \AppBundle\Controller\DefaultController
{
    /**
     * @Route("/addvote/{idSerie}", name="addvote")
     * @param $idSerie
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction($idSerie, Request $request){
        $vote = new Vote();

        $security = $this->get('security.token_storage');

        $token = $security->getToken();

        $user = $token->getUser();

        $serie = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
            ->find($idSerie);

        //echo '<pre>'; print_r($serie); echo '</pres>';

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $vote);
        $formBuilder
            ->add('Vote', ChoiceType::class, [
                    'choices'=>[
                        '0'=> 0,
                        '1'=> 1,
                        '2'=> 2,
                        '3'=> 3,
                        '4'=> 4,
                        '5'=> 5,
                        '6'=> 6,
                        '7'=> 7,
                        '8'=> 8,
                        '9'=> 9,
                        '10'=> 10
                    ]])
            ->add('Serie', EntityType::class,[
                'class' => 'AppBundle:Serie',
                'choices' => [$serie],
                ])
            ->add('User', EntityType::class, [
            'class' => 'AppBundle:User',
            'choices' => [$user],
            ])
            ->add('Voter', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($vote);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Vote bien enregistrée.');

                return $this->redirectToRoute('fiche',['id' => $idSerie]);
            }
        }

        return $this->render('default/vote/form.html.twig', array('form'=> $form->createView(),));
    }

    /**
     * @Route("/editvote/{id}", name="editvote")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request){
        $vote = new Vote();

        $security = $this->get('security.token_storage');

        $token = $security->getToken();

        $user = $token->getUser();

        $em = $this->getDoctrine()->getManager();

        $vote = $em->getRepository('AppBundle:Vote')->find($id);

        //echo '<pre>'; print_r($serie); echo '</pres>';

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $vote);
        $formBuilder
            ->add('Vote', ChoiceType::class, [
                'choices'=>[
                    '0'=> 0,
                    '1'=> 1,
                    '2'=> 2,
                    '3'=> 3,
                    '4'=> 4,
                    '5'=> 5,
                    '6'=> 6,
                    '7'=> 7,
                    '8'=> 8,
                    '9'=> 9,
                    '10'=> 10
                ]])
            ->add('Serie', EntityType::class,[
                'class' => 'AppBundle:Serie',
                'choices' => [$vote->getSerie()],
            ])
            ->add('User', EntityType::class, [
                'class' => 'AppBundle:User',
                'choices' => [$user],
            ])
            ->add('Voter', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $vote = $form->getData();
                $em->persist($vote);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Vote bien enregistrée.');

                return $this->redirectToRoute('profile');
            }
        }

        return $this->render('default/vote/update.html.twig', array('form'=> $form->createView(),));
    }
}