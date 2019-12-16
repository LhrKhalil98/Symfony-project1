<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\EditerType;
use App\Form\FilmType;


use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class FilmController extends AbstractController
{
    /**
     * @Route("/admin/films", name="films")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexadmin(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }
    /**
     * @Route("/films", name="home_films")
     * 
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('home/film.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/films/new", name="film_new" )
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $image =  $form->get('url_image')->getData(); 
            $nomImage = md5(uniqid()).'.'.$image->guessExtension() ;
            $image->move($this->getParameter('upload_directory'),$nomImage) ; 
            $film->setUrlImage($nomImage) ; 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('films');
        }

        return $this->render('film/new.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/films/{id}", name="film_show")
     * 
     */
    public function show(Film $film): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('film/show.html.twig', [
            'film' => $film,
            'acteurs'=>$film->getActeur() , 
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="film_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Film $film): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('films');
    }
    
    /**
     * @Route("/film_edit/{id}", name="film_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Film $film): Response
    {
        $form = $this->createForm(EditerType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('films');
        }

        return $this->render('film/edit.html.twig', [
            'user' => $film,
            'form' => $form->createView(),
        ]);
    }
}
