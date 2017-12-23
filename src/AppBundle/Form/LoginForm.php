<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username',TextType::class,['attr' => ['class' => 'form-control','placeholder' => 'Login']])
            ->add('_password',PasswordType::class,['attr' => ['class' => 'form-control','placeholder' => 'Password']])
            ->add('_submit',SubmitType::class,['attr' => ['class' => 'btn btn-default submit'],'label'  => 'Log in']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
            'csrf_protection' => false,
            'csrf_field_name' => '_login_csrf_token',
            'intention'       => 'authenticate'
        ));
    }
}