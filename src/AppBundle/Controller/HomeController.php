<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Valid;
use AppBundle\Form\ProfileInfoForm;
use AppBundle\Form\ProfilePassChangeForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends Controller
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @Route("/admin/", name="admin_home")
     */
    public function homeAction()
    {
        return $this->render('AppBundle::home.html.twig');
    }


    /////////////////////Profile//////////////////////////////

    /**
     * @Route("/admin/profile", name="admin_profile")
     */
    public function profileAction(Request $request)
    {
        $errors = [];
        $validator = $this->get('validator');

        //ProfileInfoForm
        $profileInfoForm = new ProfileInfoForm();
        $formInfo = $this->createForm(ProfileInfoForm::class,$profileInfoForm,
            [
                'action' => $this->generateUrl('admin_profile'),
                'attr' => ['class' => 'form-horizontal form-label-left' , 'novalidate' => '']
            ]
        );
        $formInfo->handleRequest($request);

        //ProfilePassChangeForm
        $profilePassChangeForm = new ProfilePassChangeForm($this->user);
        $formPass = $this->createForm(ProfilePassChangeForm::class,$profilePassChangeForm,
            [
                'action' => $this->generateUrl('admin_profile'),
                'attr' => ['class' => 'form-horizontal form-label-left' , 'novalidate' => '']
            ]
        );
        $formPass->handleRequest($request);

        if($formInfo->isSubmitted())
        {
            if($formInfo->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $this->user->setFirstname($profileInfoForm->getName());
                $this->user->setSurname($profileInfoForm->getSurname());
                $this->user->setEmail($profileInfoForm->getEmail());

                if($profileInfoForm->getImage() !== null)
                {
                    $oldFileName = $this->user->getThumb();
                    $file = $profileInfoForm->getImage();
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    $file->move(
                        $this->getParameter('user_image_directory'),
                        $fileName
                    );

                    if(!empty($oldFileName) && file_exists($this->getParameter('user_image_directory')."/".$oldFileName))
                        unlink($this->getParameter('user_image_directory')."/".$oldFileName);

                    $this->user->setThumb($fileName);
                }

                $em->persist($this->user);
                $em->flush();
            }

            $errors = $validator->validate($profileInfoForm);
        }
        else if($formPass->isSubmitted())
        {
            if($formPass->isValid())
            {
                $encoder_service = $this->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($this->user);
                $em = $this->getDoctrine()->getManager();

                $password = $encoder->encodePassword($profilePassChangeForm->getPassword(), $this->user->getSalt());

                $this->user->setPassword($password);

                $em->persist($this->user);
                $em->flush();
            }

            $errors = $validator->validate($profilePassChangeForm);
        }
        else
        {
            $formInfo->get('name')->setData($this->user->getFirstname());
            $formInfo->get('surname')->setData($this->user->getSurname());
            $formInfo->get('login')->setData($this->user->getUsername());
            $formInfo->get('email')->setData($this->user->getEmail());
        }

        return $this->render('AppBundle::profile.html.twig',['formInfo' => $formInfo->createView(),'formPass' => $formPass->createView(),'errors' => $errors]);
    }
}
