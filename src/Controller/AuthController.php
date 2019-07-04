<?php

  namespace App\Controller;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;

  class AuthController extends Controller {

    // /**
    //  * @Route("/login2", name="login")
    //  * @Method({"GET", "POST"})
    //  */
    // public function viewLogin() {
    //   if (isset($_POST['email'])) {
    //     return $this->redirectToRoute("userDashboard");
    //   }

    //   return $this->render('auth/login.html.twig', array('currentPage' => 'login'));
    // }

    // /**
    //  * @Route("/register")
    //  * @Method({"GET", "POST"})
    //  */
    // public function viewRegistration() {
    //   if (isset($_POST['name'])) {
    //     return $this->redirectToRoute("login");
    //   }

    //   return $this->render('auth/registration.html.twig', array('currentPage' => 'register'));
    // }

    // /**
    //  * @Route("/new-tournament")
    //  * @Method({"GET"})
    //  */
    // public function viewNewTournament() {
    //   return $this->render('tournaments/new_tournament.html.twig', array('currentPage' => 'register'));
    // }

    // /**
    //  * @Route("/new-team")
    //  * @Method({"GET"})
    //  */
    // public function viewNewTeam() {
    //   return $this->render('teams/new_team.html.twig', array('currentPage' => 'register'));
    // }
  }