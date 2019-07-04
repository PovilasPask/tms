<?php

  namespace App\Controller;

  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\Form\Extension\Core\Type\SearchType;
  use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
  use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;
  use App\Entity\Tournament;
  use App\Entity\Team;
  use App\Form\TournamentCreateType;
  use App\Entity\Game;
  use Symfony\Component\Form\Extension\Core\Type\NumberType;

  class TournamentController extends Controller {
    
    /**
     * @Route("/tournaments/invite/{id}", name ="inviteTeam")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function inviteTeam(Request $request, $id) {
      $team = $this->getDoctrine()
        ->getRepository(Team::class)
        ->find($id);

      if (!$team) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      } 

      if ($team->getTournament() != null) {
        return $this->redirectToRoute('showTeam', array('id' => $id));
      }

      $tournaments = $this->getUser()->getTournaments();

      if ($tournaments[0] == null) {
        // User has no tournaments to invite in
        //die("Neturinte turnyrų, į kuriuos galėtumėte pakviesti komandą");
        return $this->redirectToRoute('showTeam', array('id' => $id));
      }

      $isManagerOfThisTeam = false;
      if ($team->getUser()->getId() == $this->getUser()->getId()) {
        $isManagerOfThisTeam = true;
      }

      $tournamentChoices = [];

      foreach ($tournaments as $tournament) {
        if ($tournament->getIsStarted() === false) {
          $tournamentChoices[$tournament->getName()] = $tournament;
        }
      }

      $form = $this->createFormBuilder(null)
        ->add('tournament', ChoiceType::class, [
          'choices'  => $tournamentChoices,
          'attr' => [
              'class' => 'form-control',
          ]
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Pakviesti',
        'attr' => [
            'class' => 'btn btn-secondary form-control',
        ]
      ])
      ->getForm();
      
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $tournament = $form['tournament']->getData();
        if ($tournament->getIsStarted() == false) {
          $entityManager = $this->getDoctrine()->getManager();
          $team->setTournament($tournament);
          $team->setState(2);
          $entityManager->flush();
        }
        return $this->redirectToRoute('showTeam', array('id' => $team->getId()));
      }
      
      return $this->render('tournaments/invite_team.html.twig', array(
        'currentPage' => 'userDashboard',
        'form' => $form->createView(),
        'isManagerOfThisTeam' => $isManagerOfThisTeam,
        'teamId' => $team->getId(),
        'teamName' => $team->getName(),
      ));
    }


    /**
     * @Route("/tournaments/new", name ="newTournament")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function newTournament(Request $request) {
      $tournament = new Tournament();
      $form = $this->createForm(TournamentCreateType::class, $tournament, array(
        'doctrine' => $this->getDoctrine(),
      ));
      
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $tournament->setUser($this->getUser());
        $tournament->setIsStarted(false);
        $tournament->setIsEnded(false);
        $tournament->setCode($this->getRandomCode());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tournament);
        $entityManager->flush();
        return $this->redirectToRoute('userDashboard');
      }
      
      return $this->render('tournaments/new_tournament.html.twig', array(
        'currentPage' => 'userDashboard',
        'form' => $form->createView()
      ));
    }


    /**
     * @Route("/tournaments/{id}/delete")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function TournamentDelete(Request $request, $id) {
      $tournament = $this->getDoctrine()
      ->getRepository(Tournament::class)
      ->find($id);

      
      if (!$tournament) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($tournament->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($tournament);
      $entityManager->flush();
      return $this->redirectToRoute('userDashboard');
    }

    /**
     * @Route("/tournaments/{id}/start")
     * @Method({"GET"})
     * @isGranted("ROLE_MANAGER")
     */
    public function TournamentStart(Request $request, $id) {
      $tournament = $this->getDoctrine()
      ->getRepository(Tournament::class)
      ->find($id);

      
      if (!$tournament) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($tournament->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }

      if ($tournament->getIsStarted() == true) {
        return $this->redirectToRoute('showTournament', array('id' => $id));
      }
      
      $shedule = $this->generateShedule($tournament);
      $entityManager = $this->getDoctrine()->getManager();
      foreach ($shedule as $row) {
        $game = new Game();
        $game->setTournament($tournament);
        $game->setDate(new \DateTime('2019-01-01 00:00'));
        $game->setRound($row['round']);
        $game->setGameNr($row['gameNr']);
        $game->setIsPlayoffsGame($row['isPlayoffsGame']);
        $game->setHomeTeam($row['homeTeam']);
        $game->setAwayTeam($row['awayTeam']);
        $entityManager->persist($game);
      }
      $tournament->setIsStarted(true);
      $entityManager->flush();
      return $this->redirectToRoute('showTournament', array('id' => $id));
    }


    /**
     * @Route("/tournaments/{id}/teams", name="showTournamentTeams")
     * @Method({"GET"})
     * @isGranted("ROLE_MANAGER")
     */
    public function TournamentTeams(Request $request, $id) {
      $tournament = $this->getDoctrine()
      ->getRepository(Tournament::class)
      ->find($id);

      if (!$tournament) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }
      if ($tournament->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }

      $teams = $tournament->getTeams();

      $teams1 = [];
      $teams2 = [];
      $teams3 = [];
      foreach ($teams as $team) {
        switch ($team->getState()) {
          case 1:
            $teams1[] = $team;
            break;
          case 2:
            $teams2[] = $team;
            break;
          case 3:
            $teams3[] = $team;
            break;
        }
      }

      return $this->render('tournaments/tournament_teams.html.twig', array(
        'currentPage' => 'userDashboard',
        'tournament' => $tournament,
        'teams1' => $teams1,
        'teams2' => $teams2,
        'teams3' => $teams3,
      ));
    }


    /**
     * @Route("/tournaments/{id}", name ="showTournament")
     * @Method({"GET", "POST"})
     */
    public function showTournament(Request $request, $id) {
      $tournament = $this->getDoctrine()
        ->getRepository(Tournament::class)
        ->find($id);

      if (!$tournament) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      // Checking if this user is the manager of the tournament
      $isManagerOfThisTournament = false;
      $availableTeams = [];

      if ($this->isGranted('ROLE_MANAGER')) {
        if ($tournament->getUser()->getId() == $this->getUser()->getId()) {
          $isManagerOfThisTournament = true;
        }
        $teams = $this->getUser()->getTeams();
        foreach ($teams as $team) {
          if ($team->getState() == 0) {
            $availableTeams[] = $team;
          }
        }
      }

      $games = $tournament->getGames();
      $tableRows = $this->getLeagueTable($tournament);

      return $this->render('tournaments/tournament_info.html.twig', array(
        'currentPage' => 'userDashboard',
        'tournament' => $tournament,
        'availableTeams' => $availableTeams,
        'isManagerOfThisTournament' => $isManagerOfThisTournament,
        'tableRows' => $tableRows,
        'games' => $games,
      ));
    }
    

    /**
     * @Route("/games/{id}/edit")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function EditGame(Request $request, $id) {
      $game = $this->getDoctrine()
      ->getRepository(Game::class)
      ->find($id);

      
      if (!$game) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($game->getTournament()->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $form = $this->createFormBuilder(null)
        ->add('date', DateTimeType::class , [
          'attr' => [
            'class' => 'form-control input-field-green',
          ],
          'widget' => 'single_text',
          'data' => $game->getDate(),
        ])
        ->add('submit', SubmitType::class, [
          'label' => 'Išsaugoti',
          'attr' => [
            'class' => 'btn btn-secondary',
          ]
        ])
        ->getForm();

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $game->setDate($form['date']->getData());
        $entityManager->flush();
        return $this->redirectToRoute('showTournament', array('id' => $game->getTournament()->getId()));
      }

      return $this->render('tournaments/game_edit.html.twig', array(
        'currentPage' => 'userDashboard',
        'tournament' => $game->getTournament(),
        'game' => $game,
        'form' => $form->createView(),
      ));
    }


    /**
     * @Route("/games/{id}/enterScore", name="EnterScore")
     * @Method({"GET", "POST"})
     * @isGranted("ROLE_MANAGER")
     */
    public function EnterScore(Request $request, $id) {
      $game = $this->getDoctrine()
      ->getRepository(Game::class)
      ->find($id);

      
      if (!$game) {
        return $this->render('errors/notFound.html.twig', array(
          'currentPage' => 'notFound',
        ));
      }

      if ($game->getTournament()->getUser()->getId() != $this->getUser()->getId()) {
        return $this->redirectToRoute('userDashboard');
      }
      
      $form = $this->createFormBuilder(null)
        ->add('homeScore', NumberType::class , [
          'attr' => [
            'class' => 'form-control input-field-green',
          ],
        ])
        ->add('awayScore', NumberType::class , [
          'attr' => [
            'class' => 'form-control input-field-green',
          ],
        ])
        ->add('submit', SubmitType::class, [
          'label' => 'Išsaugoti',
          'attr' => [
            'class' => 'btn btn-secondary',
          ]
        ])
        ->getForm();

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $hs = $form['homeScore']->getData();
        $as = $form['awayScore']->getData();
        if ($hs >= 0 && $hs < 50 && $as >= 0 && $as < 50) {
          $entityManager = $this->getDoctrine()->getManager();
          $game->setHomeScore($hs);
          $game->setAwayScore($as);
          $entityManager->flush();
          return $this->redirectToRoute('showTournament', array('id' => $game->getTournament()->getId()));
        } else {
          return $this->redirectToRoute('EnterScore', array('id' => $id));
        }
      }

      return $this->render('tournaments/game_enter_score.html.twig', array(
        'currentPage' => 'userDashboard',
        'tournament' => $game->getTournament(),
        'game' => $game,
        'form' => $form->createView(),
      ));
    }


    /**
     * @Route("/tournaments", name="list-all-tournaments-public")
     * @Method({"GET"})
     */
    public function index(Request $request) {

      $form = $this->createFormBuilder(null, [ 'method' => 'GET' ])
        ->add('text', SearchType::class , [
          'attr' => [
            'class' => 'form-control input-field-green',
            'placeholder' => 'Turnyro kodas arba pavadinimas',
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
        $tournaments = $this->getDoctrine()
          ->getRepository(Tournament::class)
          ->search($form['text']->getData());
      } else {
        $tournaments = $this->getDoctrine()
          ->getRepository(Tournament::class)
          ->search('');
      }

      return $this->render('tournaments/index.html.twig', array(
        'currentPage' => 'tournaments',
        'tournaments' => $tournaments,
        'form' => $form->createView(),
      ));
    }


    // Generates random code in format AAAA-1111
    private function getRandomCode() {
      $code = "";
      for ($i=0; $i < 4; $i++) { 
        $code .= chr(random_int(66, 90));
      }
      $code .= '-';
      for ($i=0; $i < 4; $i++) { 
        $code .= random_int(0, 9);
      }
      return $code;
    }

    private function getLeagueTable($tournament) {
      $tableRows = [];
      $teams = $tournament->getTeams();
      $count = 0;

      foreach ($teams as $team) {
        if ($team->getState() === 3) {
          $games = $this->getDoctrine()
          ->getRepository(Game::class)
          ->findAllLeagueGamesByTeam($team->getId());
  
          $gamesPlayed = 0;
          $wins = 0;
          $draws = 0;
          $loses = 0;
          $goalsF = 0;
          $goalsA = 0;
          $points = 0;
  
          foreach ($games as $game) {
            if ($game->getHomeScore() !== null) {
              $gamesPlayed++;
              if ($game->getHomeTeam()->getId() === $team->getId()) {
                // $team is home team
                $goalsF += $game->getHomeScore();
                $goalsA += $game->getAwayScore();
                if ($game->getHomeScore() === $game->getAwayScore()) {
                  // Home draw
                  $draws++;
                  $points += 1;
                } else if($game->getHomeScore() < $game->getAwayScore()) {
                  // Home loss
                  $loses++;
                } else {
                  // Home win
                  $wins++;
                  $points += 3;
                }
              } else {
                // $team is away team
                $goalsF += $game->getAwayScore();
                $goalsA += $game->getHomeScore();
                if ($game->getHomeScore() === $game->getAwayScore()) {
                  // Away draw
                  $draws++;
                  $points += 1;
                } else if($game->getHomeScore() > $game->getAwayScore()) {
                  // Away loss
                  $loses++;
                } else {
                  // Away win
                  $wins++;
                  $points += 3;
                }
              }
            }
          }
  
          if ($team->getState() == 3) {
            $count++;
            $tableRows[] = [
              'id' => $team->getId(),
              'name' => $team->getName(),
              'gamesPlayed' => $gamesPlayed,
              'wins' => $wins,
              'draws' => $draws,
              'loses' => $loses,
              'goalsF' => $goalsF,
              'goalsA' => $goalsA,
              'points' => $points,
            ];
          }
        }
      }
      usort($tableRows, function($a, $b)
      {
        if ($a['points'] === $b['points']) {
          return strcmp($a['name'], $b['name']);
        } else {
          return ($a['points'] < $b['points']? 1: -1);
        }
      });
      return $tableRows;
    }

    private function generateShedule($tournament) {
      $games = [];
      $teams = [];
      $allTeams = $tournament->getTeams();
      $count = 0;
      $roundCount = 0;

      foreach ($allTeams as $team) {
        if ($team->getState() === 3) {
          $teams[] = $team;
          $count++;
        }
      }
      if ($count < 2) { return []; }

      if ($count % 2 == 1) {
        $teams[] = null;
        $count++;
      }

      $first = array_shift($teams);
      for ($i=1; $i < $count; $i++) { 
        $roundCount++;
        if ($teams[$count - 2] != null) {
          $games[] = [
            'round' => $i,
            'gameNr' => 1,
            'isPlayoffsGame' => false,
            'homeTeam' => ($i % 2 != 0 ? $first : $teams[$count - 2]),
            'awayTeam' => ($i % 2 != 0 ? $teams[$count - 2] : $first),
          ];
        }
        for ($j=1; $j < $count/2; $j++) { 
          if ($teams[$j - 1] != null && $teams[$count - $j - 2] != null) {
            $games[] = [
              'round' => $i,
              'gameNr' => 1,
              'isPlayoffsGame' => false,
              'homeTeam' => ($i % 2 != 0 ? $teams[$j - 1] : $teams[$count - $j - 2]),
              'awayTeam' => ($i % 2 != 0 ? $teams[$count - $j - 2] : $teams[$j - 1]),
            ];
          }
        }

        array_unshift($teams, array_pop($teams));
      }
      $gameCount = sizeof($games);
      for ($i=0; $i < $gameCount; $i++) {
        $games[] = [
          'round' => $roundCount + $games[$i]['round'],
          'gameNr' => 2,
          'isPlayoffsGame' => false,
          'homeTeam' => $games[$i]['awayTeam'],
          'awayTeam' => $games[$i]['homeTeam'],
        ];
      }
      return $games;
    }
  }