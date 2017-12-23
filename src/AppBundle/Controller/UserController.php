<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->getInt('page', 1);/*page number*/
        $limit = 10; /*limit per page*/

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $page,
            $limit
        );

        return $this->render('AppBundle:user:users.html.twig',['users'=>$pagination,'startCount' => ($page-1)*$limit ]);
    }

    /**
     * @Route("/admin/user/insert", name="admin_user_insert")
     */
    public function insertAction(Request $request)
    {
        //Form
        $newUser = new User();

        $formUser = $this->createForm(UserForm::class,$newUser,
            [
                'action' => $this->generateUrl('admin_user_insert'),
                'attr' => ['class' => 'form-horizontal form-label-left' , 'novalidate' => '']
            ]
        );
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid())
        {
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($newUser);
            $newUser->setPassword( $encoder->encodePassword($newUser->getPassword(), $newUser->getSalt()) );
            $em = $this->getDoctrine()->getManager();

            $em->persist($newUser);
            $em->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('AppBundle:user:usersAddEdit.html.twig',['formUser'=>$formUser->createView(),'errors' => $formUser->getErrors(true)]);
    }

    /**
     * @Route("/admin/user/update/{user}", name="admin_user_update")
     */
    public function editAction(Request $request, User $user)
    {
        $formUser = $this->createForm(UserForm::class,$user,
            [
                'action' => $this->generateUrl('admin_user_update',['user'=>$user->getId()]),
                'attr' => ['class' => 'form-horizontal form-label-left' , 'novalidate' => '']
            ]
        );
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid())
        {
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            $user->setPassword( $encoder->encodePassword($user->getPassword(), $user->getSalt()) );

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('AppBundle:user:usersAddEdit.html.twig',['formUser'=>$formUser->createView(),'errors' => $formUser->getErrors(true)]);
    }

    /**
     * @Route("/admin/user/delete/{user}", name="admin_user_delete")
     */
    public function deleteAction(User $user)
    {
        if($user === null) return $this->redirectToRoute('admin_users');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_users');
    }
}
