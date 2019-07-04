<?php

  namespace App\Controller;

  use App\Form\TeamType;
  use App\Entity\Team;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

  /**
   * @isGranted("ROLE_USER")
   */
  class UserController extends Controller {

    /**
     * @Route("user-dashboard/", name ="userDashboard")
     * @Method({"GET"})
     */
    public function index() {
      if($this->isGranted('ROLE_ADMIN')){
        $data = array(
          "myTeams" => array(
            array("name" => "Chebra", "id" => "1"),
            array("name" => "Danspin", "id" => "2")
          ),
          "myTournaments" => array ()
        );
        return $this->render('admin/dashboard.html.twig', array('data' => $data, 'currentPage' => 'userDashboard'));

      } elseif($this->isGranted('ROLE_REFEREE')){
        $data = array(
          "myTeams" => array(
            array("name" => "Chebra", "id" => "1"),
            array("name" => "Danspin", "id" => "2")
          ),
          "myTournaments" => array ()
        );
        return $this->render('referee/dashboard.html.twig', array('data' => $data, 'currentPage' => 'userDashboard'));

      } else {
        $data = array(
          "myTeams" => $this->getUser()->getTeams(),
          "myTournaments" => $this->getUser()->getTournaments(),
        );
        return $this->render('user/index.html.twig', array('data' => $data, 'currentPage' => 'userDashboard'));
      }
    }
  }