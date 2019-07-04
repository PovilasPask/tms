<?php

  namespace App\Controller;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\Routing\Annotation\Route;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\Form\Extension\Core\Type\SearchType;
  use Symfony\Component\Form\Extension\Core\Type\SubmitType;

  class TmsController extends Controller {

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index() {
      $form = $this->createFormBuilder(null, [ 'method' => 'GET', 'action' => '/tournaments' ])
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
      
      return $this->render('tms/index.html.twig', [
        'currentPage' => 'home',
        'form' => $form->createView()
        ]);
    }
  }