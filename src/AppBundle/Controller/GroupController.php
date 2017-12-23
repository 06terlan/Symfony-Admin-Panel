<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserGroup;
use AppBundle\Form\GroupsForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class GroupController extends Controller
{
    /**
     * @Route("/admin/groups", name="admin_groups")
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->getInt('page', 1);/*page number*/
        $limit = 10; /*limit per page*/

        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('AppBundle:UserGroup')->findBy([], ['id' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $groups, /* query NOT result */
            $page,
            $limit
        );

        return $this->render('AppBundle:group:groups.html.twig',['groups'=>$pagination,'startCount' => ($page-1)*$limit ]);
    }

    /**
     * @Route("/admin/groups/insert", name="admin_groups_insert")
     */
    public function insertAction(Request $request)
    {
        //Form
        $userGroup = new UserGroup();

        $formGroup = $this->createForm(GroupsForm::class, $userGroup);
        $formGroup->handleRequest($request);

        if($formGroup->isSubmitted() && $formGroup->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userGroup);
            $em->flush();

            return $this->redirectToRoute('admin_groups');
        }

        return $this->render('AppBundle:group:edit.html.twig',['formGroup'=>$formGroup->createView(),'errors' => $formGroup->getErrors(true)]);
    }

    /**
     * @Route("/admin/groups/update/{userGroup}", name="admin_groups_update")
     */
    public function editAction(Request $request, UserGroup $userGroup)
    {
        $formGroup = $this->createForm(GroupsForm::class, $userGroup);
        $formGroup->handleRequest($request);

        if($formGroup->isSubmitted() && $formGroup->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_groups');
        }

        return $this->render('AppBundle:group:edit.html.twig',['formGroup'=>$formGroup->createView(),'errors' => $formGroup->getErrors(true)]);
    }

    /**
     * @Route("/admin/groups/delete/{userGroup}", name="admin_groups_delete")
     */
    public function deleteAction(UserGroup $userGroup)
    {
        if($userGroup === null) return $this->redirectToRoute('admin_groups');

        $users = $this->getDoctrine()->getRepository(User::class);
        $users = $users->findByRole($userGroup->getId());

        if( count($users) > 0 )
        {
            //$form = $this->createForm(GroupsForm::class)->addError(new FormError('Hi'));

            $this->redirectToRoute('admin_groups',[ 'cant' => 1 ]);
        }



        //$em = $this->getDoctrine()->getManager();
        //$em->remove($userGroup);
        //$em->flush();

        return $this->redirectToRoute('admin_groups');
    }
}
