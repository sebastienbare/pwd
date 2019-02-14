<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    /*
     * https://github.com/sebastienbare/pwd.git
     * adminuser
     * sebaetcharlotte
     */
    /**
     * @return array
     */
    public function connection(){
        if(!empty($_SESSION['_sf2_attributes']['_security_main'])) {
            $session = $_SESSION;
        }
        else{
            $session = [];
        }

        return $session;
    }

    /**
     * @Route("/")
     */
    public function redirigeAction(){
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/index", name="home")
     */
    public function indexAction()
    {
        $session = $this->connection();

        $serie = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
            ->findAll();

        return $this->render('default/index.html.twig', array(
            'session' => $session,
            'serie' => $serie,
        ));
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction()
    {
        $session = $this->connection();

        $user = $this->getUser();

        $historique = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Vote')
            ->findByuser($user->getId());

        if($user != null) {
            return $this->render('default/profile.html.twig', array(
                'session' => $session,
                'user' => $user,
                'historique' => $historique,
            ));
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/genre", name="genre")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function genreAction(){
        $session = $this->connection();

        $genres = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Genre')
            ->findAll();

        $serie = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
            ->findAll();


        return $this->render('default/genre.html.twig', array(
            'session' => $session,
            'genre' => $genres,
            'serie' => $serie,
        ));
    }

    public function getOrm($appbundle){
        $data = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository($appbundle)
            ->findAll();

        foreach($data as $dat)
            $datas[] = $dat->getId();

        return $datas;
    }

    /**
     * @Route("/producteur", name="producteur")
     */
    public function producteurAction(){

        $session = $this->connection();

        $producteurs = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Producteur')
            ->getAll();

        $producteur = $this->getOrm('AppBundle:Producteur');

        $serie = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
            ->findBy(['producteur' => $producteur],
                ['nom' => 'DESC']);

        //echo '<pre>'; print_r($serie); echo '</pre>';

        return $this->render('default/producteur.html.twig', array(
            'session' => $session,
            'serie' => $serie,
            'producteurs' => $producteurs,
        ));
    }

    /**
     * @Route("/acteur", name="acteur")
     */

    public function acteurAction(){

        $session = $this->connection();

        $acteurs = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Acteur')
            ->findall();

        $acteur = $this->getOrm('AppBundle:Acteur');

        $serie = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
            ->findBy(
                ['acteur' => $acteur],
                ['nom' => 'DESC']
            );

        return $this->render('default/acteur.html.twig', array(
            'session' => $session,
            'acteurs' => $acteurs,
            'serie' => $serie,
        ));
    }

    /**
     * @Route("/fiche/{id}", name="fiche")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ficheAction($id){

        if(!empty($id)) {
            $session = $this->connection();

            $serie = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Serie')
                ->findOneByid($id);

            $user = $this->getUser();

            if ($user != null) {
                $vote = $this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('AppBundle:Vote')
                    ->findByuser($user->getId());
            } else {
                $vote = [];
            }

            $vot = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:Vote')
                ->findByserie($serie->getId());

            $noteMoyenne = 0;

            foreach ($vot as $votes) {
                $noteMoyenne += $votes->getVote();
            }

            if ($noteMoyenne > 0 && count($vote) > 0)
                $noteMoyenne = $noteMoyenne / count($vote);

            return $this->render('default/fiche.html.twig', array(
                'session' => $session,
                'serie' => $serie,
                'vote' => $vote,
                'note' => $noteMoyenne,
            ));
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/admin/", name="admin")
     */
    public function adminAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Serie')
        ;
        $serie = $repository->findAll();

        return $this->render('default/admin/index.html.twig',array(
            'serie' => $serie
        ));
    }

    /**
     * @Route("/admin/producteur", name="admin/producteur")
     */
    public function adminproducteurAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Producteur')
        ;
        $producteur = $repository->findAll();

        return $this->render('default/admin/producteur.html.twig',array(
            'producteur' => $producteur
        ));
    }

    /**
     * @Route("/admin/acteur", name="admin/acteur")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminacteurAction(){

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Acteur')
        ;
        $acteur = $repository->findAll();

        return $this->render('default/admin/acteur.html.twig',array(
            'acteur' => $acteur
        ));
    }

    /**
     * @Route("/admin/genre", name="admin/genre")
     */
    public function admingenreAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Genre')
        ;
        $genre = $repository->findAll();

        return $this->render('default/admin/genre.html.twig',array(
            'genre' => $genre
        ));
    }

    /**
     * @Route("/admin/resume", name="admin/resume")
     */
    public function adminresumeAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Resume')
        ;
        $resume = $repository->findAll();

        return $this->render('default/admin/resume.html.twig',array(
            'resume' => $resume
        ));
    }

    /**
     * @Route("/admin/user", name="admin/user")
     */
    public function adminuserAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:User')
        ;
        $user = $repository->findAll();

        return $this->render('default/admin/user.html.twig',array(
            'user' => $user
        ));
    }

    /**
     * @Route("/admin/image", name="admin/image")
     */
    public function adminimageAction(){
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Image')
        ;
        $image = $repository->findAll();

        return $this->render('default/admin/image.html.twig',array(
            'image' => $image
        ));
    }
}
