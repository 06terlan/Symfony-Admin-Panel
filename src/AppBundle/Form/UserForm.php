<?php
namespace AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Entity\UserGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public $groupName;
    public $groupKey;


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,['label'=>'Name','attr' => ['required'=>'','data-validate-length-range' => '1,100','class' => 'form-control has-feedback-left','placeholder' => 'Name']])
            ->add('surname',TextType::class,['label'=>'Surname','required'=>false,'attr' => ['data-validate-length-range' => ',100','class' => 'form-control has-feedback-left','placeholder' => 'Surname']])
            ->add('username',TextType::class,['label'=>'Login','attr' => ['required'=>'','data-validate-length-range' => '5,100','class' => 'form-control has-feedback-left','placeholder' => 'Login']])
            ->add('email',EmailType::class,['label'=>'Email','attr' => ['required'=>'','class' => 'form-control has-feedback-left','placeholder' => 'Email']])
            ->add('password',PasswordType::class,['label'=>'Password','attr' => ['required'=>'','data-validate-length-range' => '5,20','class' => 'form-control has-feedback-left','placeholder' => 'Password']])
            ->add('role',EntityType::class,['label'=>'Role','attr' => ['required'=>'','class' => 'form-control','placeholder' => 'Role'],'class' => UserGroup::class,'choice_label' => 'groupName'])
            ->add('save',SubmitType::class,['label'=>'Save','attr' => ['class' => 'btn btn-success']])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_tooken',
            'csrf_token_id'   => 'form_user',
            'validation_groups' => array('insertion')
        ));
    }

    /**
     * @return mixed
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @param mixed $groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * @return mixed
     */
    public function getGroupKey()
    {
        return $this->groupKey;
    }

    /**
     * @param mixed $groupKey
     */
    public function setGroupKey($groupKey)
    {
        $this->groupKey = $groupKey;
    }
}