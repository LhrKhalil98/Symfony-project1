<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
   /**
     * @Route("/admin/users", name="users" )
     * @IsGranted("ROLE_ADMIN")
     */
    public function afficher(UserRepository $userRepository): Response
    {
        return $this->render('user/afficher_users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new_admin", name="admin_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newAdmin(Request $request ,  UserPasswordEncoderInterface $encode): Response
    {
        $admin = new User();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encode->encodePassword( $admin ,$admin->getPassword());
            $admin->setPassword($hash);
            $admin->setRoles(array('ROLE_ADMIN'));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($admin);
            $manager->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('user/new_admin.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/inscription", name="inscription")
     */
    public function newClient(Request $request ,  UserPasswordEncoderInterface $encode)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
         }
        $client = new User();
        $form = $this->createForm(AdminType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encode->encodePassword( $client ,$client->getPassword());
            $client->setPassword($hash);
            $client->setRoles(array('ROLE_USER'));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($client);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/inscription.html.twig', [
            'admin' => $client,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/signup", name="signup")
     * 
     */
    public function signup(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     * 
     */
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/admin_delete/{id}", name="user_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('users');
    }
}
