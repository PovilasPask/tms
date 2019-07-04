<?php

  namespace App\Controller;

  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
  use Symfony\Component\Form\Extension\Core\Type\TextType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use Symfony\Component\Form\Extension\Core\Type\SearchType;
  use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
  use \DateTime;
  use App\Entity\Team;
  use App\Form\TeamType;
  use App\Entity\Player;
  use App\Form\PlayerType;
  use App\Entity\Country;
  use App\Entity\Tournament;
  use App\Entity\Game;

  class TeamController extends Controller {

    /**
     * @Route("/teams/players/{id}/edit")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function PlayerEdit(Request $request, $id) {
      $player = $this->getDoctrine()
      ->getRepository(Player::class)
      ->find($id);

      
      if (!$player) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      $team = $player->getTeam();
      if ($team->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $form = $this->createForm(PlayerType::class, $player);
      
      $format = 'Y-m-d';
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        if (checkdate($form['month']->getData(), $form['day']->getData(), $form['year']->getData())) {
          $date = $form['year']->getData() . '-' .
                  $form['month']->getData() . '-' .
                  $form['day']->getData();

          $date = DateTime::createFromFormat($format, $date);
          $player->setBDate($date);
        } else {
          return $this->render('teams/edit_player.html.twig', array(
            'currentPage' => 'userDashboard',
            'teamName' => $team->getName(),
            'teamId' => $team->getId(),
            'playerName' => $player->getName(),
            'dateOk' => false,
            'form' => $form->createView()
          ));
        }

        if ($form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
        }
      }
      
      return $this->render('teams/edit_player.html.twig', array(
        'currentPage' => 'userDashboard',
        'teamName' => $team->getName(),
        'teamId' => $team->getId(),
        'playerName' => $player->getName(),
        'dateOk' => true,
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("/teams/players/{id}/delete")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function PlayerDelete(Request $request, $id) {
      $player = $this->getDoctrine()
      ->getRepository(Player::class)
      ->find($id);

      
      if (!$player) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      $team = $player->getTeam();
      if ($team->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($player);
      $entityManager->flush();
      return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
    }


    /**
     * @Route("/teams/registerToTournament/{id}", name ="registerTeamToTournament")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function registerTeamToTournament(Request $request, $id) {
      $tournament = $this->getDoctrine()
        ->getRepository(Tournament::class)
        ->find($id);

      if (!$tournament) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($tournament->getIsStarted() == true) {
        // Tournament has already started
        return $this->redirectToRoute('showTournament', array('id' => $id));
      }

      $teams = $this->getUser()->getTeams();
      $teamChoices = [];

      foreach ($teams as $team) {
        if ($team->getState() == 0) {
          $teamChoices[$team->getName()] = $team;
        }
      }

      if (sizeof($teamChoices) == 0) {
        // User has no teams to register
        //die('User has no teams to register');
        return $this->redirectToRoute('showTournament', array('id' => $id));
      }

      $isManagerOfThisTournament = false;
      if ($tournament->getUser()->getId() == $this->getUser()->getId()) {
        $isManagerOfThisTournament = true;
      }


      $form = $this->createFormBuilder(null)
        ->add('team', ChoiceType::class, [
          'choices'  => $teamChoices,
          'attr' => [
              'class' => 'form-control',
          ]
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Registruoti',
        'attr' => [
            'class' => 'btn btn-secondary form-control',
        ]
      ])
      ->getForm();
      
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

        $entityManager = $this->getDoctrine()->getManager();
        $team = $form['team']->getData();
        if ($team->getState() !== 0) {
          return $this->redirectToRoute('showTournament', array('id' => $id));
        }
        $team->setTournament($tournament);
        $team->setState(1);
        $entityManager->flush();
        return $this->redirectToRoute('showTournament', array('id' => $id));
      }
      
      return $this->render('tournaments/register_to_tournament.html.twig', array(
        'currentPage' => 'userDashboard',
        'form' => $form->createView(),
        'isManagerOfThisTournament' => $isManagerOfThisTournament,
        'tournament' => $tournament,
      ));
    }


    /**
     * @Route("/teams/{id}/delete")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function TeamDelete(Request $request, $id) {
      $team = $this->getDoctrine()
      ->getRepository(Team::class)
      ->find($id);

      
      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($team->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($team);
      $entityManager->flush();
      return $this->redirectToRoute('userDashboard');
    }


    /**
     * @Route("/teams/{id}/edit")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function teamEdit(Request $request, $id) {
      $team = $this->getDoctrine()
      ->getRepository(Team::class)
      ->find($id);
      
      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }
      if ($team->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('list-all-teams-public');
      }
      
      $form = $this->createForm(TeamType::class, $team, array(
        'doctrine' => $this->getDoctrine(),
      ));
      
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
      }
      
      return $this->render('teams/edit_team.html.twig', array(
        'currentPage' => 'userDashboard',
        'teamName' => $team->getName(),
        'teamId' => $team->getId(),
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("/teams/{id}/addPlayer")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function addPlayer(Request $request, $id) {
      $team = $this->getDoctrine()
      ->getRepository(Team::class)
      ->find($id);
      
      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }
      if ($team->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('list-all-teams-public');
      }
      
      $player = new Player();
      $form = $this->createForm(PlayerType::class, $player);
      
      $format = 'Y-m-d';
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        if (checkdate($form['month']->getData(), $form['day']->getData(), $form['year']->getData())) {
          $date = $form['year']->getData() . '-' .
                  $form['month']->getData() . '-' .
                  $form['day']->getData();

          $date = DateTime::createFromFormat($format, $date);
          $player->setBDate($date);
        } else {
          return $this->render('teams/new_player.html.twig', array(
            'currentPage' => 'userDashboard',
            'teamName' => $team->getName(),
            'teamId' => $team->getId(),
            'dateOk' => false,
            'form' => $form->createView()
          ));
        }

        if ($form->isValid()) {
          $player->setGoalCount(0);
          $player->setTeam($team);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($player);
          $entityManager->flush();
          return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
        }
      }
      
      return $this->render('teams/new_player.html.twig', array(
        'currentPage' => 'userDashboard',
        'teamName' => $team->getName(),
        'teamId' => $team->getId(),
        'dateOk' => true,
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("/teams/{id}/accept")
     * @Method({"POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function acceptInvitation($id) {
      $team = $this->getDoctrine()
      ->getRepository(Team::class)
      ->find($id);

      
      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      
      $entityManager = $this->getDoctrine()->getManager();
      if ($team->getState() == 1) {
        // Team registrated to a tournament
        if ($team->getTournament()->getUser()->getId() != $this->getUser()->getId()) {
          return $this->redirectToRoute('userDashboard');
        }
        $team->setState(3);
        $entityManager->flush();
        return $this->redirectToRoute('showTournamentTeams', array('id' => $team->getTournament()->getId()));
      } else if ($team->getState() == 2) {
        // Tournament invited the team      
        if ($team->getUser()->getId() != $this->getUser()->getId()) {
          return $this->redirectToRoute('userDashboard');
        }
        $team->setState(3);
        $entityManager->flush();
        return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
      } else {
        return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
      }
    }


    /**
     * @Route("/teams/{id}/decline")
     * @Method({"POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function declineInvitation($id) {
      $team = $this->getDoctrine()
      ->getRepository(Team::class)
      ->find($id);

      
      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      
      $entityManager = $this->getDoctrine()->getManager();
      if ($team->getState() == 1 || $team->getState() == 2) {
        $tournament = $team->getTournament();
        if ($tournament->getUser()->getId() == $this->getUser()->getId()) {
          // Tournament manager
          $team->setState(0);
          $team->setTournament(null);
          $entityManager->flush();
          return $this->redirectToRoute('showTournamentTeams', array('id' => $tournament->getId()));
          return $this->redirectToRoute('userDashboard');
        } else if($team->getUser()->getId() == $this->getUser()->getId()) {
          // Tournament invited the team
          $team->setState(0);
          $team->setTournament(null);
          $entityManager->flush();
          return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
        } else {
          return $this->redirectToRoute('userDashboard');
        }
      } else {
        return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
      }
    }


    /**
     * @Route("/teams/{id}", name="showTeam")
     * @Method({"GET"})
     */
    public function teamInfo($id) {
      $team = $this->getDoctrine()
        ->getRepository(Team::class)
        ->find($id);

      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      $isManagerOfThisTeam = false;
      $isManager = false;
      $availableTournaments = [];

      if ($this->isGranted('ROLE_MANAGER')) {
        $isManager = true;
        if ($team->getUser()->getId() == $this->getUser()->getId()) {
          $isManagerOfThisTeam = true;
        }
        $tournaments = $this->getUser()->getTournaments();
        foreach ($tournaments as $tournament) {
          if ($tournament->getIsStarted() == false) {
            $availableTournaments[] = $tournament;
          }
        }
      }

      $games = $this->getDoctrine()
      ->getRepository(Game::class)
      ->findAllLeagueGamesByTeam($id);


      return $this->render('teams/team_info.html.twig', array(
        'currentPage' => ($isManagerOfThisTeam ? 'userDashboard' : 'teams'),
        'team' => $team,
        'isManager' => $isManager,
        'isManagerOfThisTeam' => $isManagerOfThisTeam,
        'availableTournaments' => $availableTournaments,
        'games' => $games,
        'players' => $team->getPlayers()
      ));
    }


    /**
     * @Route("/teams", name="list-all-teams-public")
     * @Method({"GET"})
     */
    public function index(Request $request) {

      $form = $this->createFormBuilder(null, [ 'method' => 'GET' ])
        ->add('text', SearchType::class , [
          'attr' => [
            'class' => 'form-control input-field-green',
            'placeholder' => 'Komandos pavadinimas',
          ],
          'required' => false,
        ])
        ->add('submit', SubmitType::class, [
          'label' => 'Ieškoti',
          'attr' => [
            'class' => 'btn btn-secondary',
          ]
        ])
        ->getForm();

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $teams = $this->getDoctrine()
          ->getRepository(Team::class)
          ->search($form['text']->getData());
      } else {
        $teams = $this->getDoctrine()
          ->getRepository(Team::class)
          ->search('');
      }

      return $this->render('teams/index.html.twig', array(
        'currentPage' => 'teams',
        'teams' => $teams,
        'form' => $form->createView(),
      ));
    }


    /**
     * @Route("/new-team", name ="newTeam")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function newTeam(Request $request) {
      $team = new Team();
      $form = $this->createForm(TeamType::class, $team, array(
        'doctrine' => $this->getDoctrine(),
      ));

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        // $country = $this->getDoctrine()->getRepository(Country::class)->find(1);
        // $team->setCountry($country);
        $team->setUser($this->getUser());
        $team->setState(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($team);
        $entityManager->flush();
        return $this->redirectToRoute('userDashboard');
      }

      return $this->render('teams/new_team.html.twig', array(
        'currentPage' => 'userDashboard',
        'form' => $form->createView()
      ));
    }
  }