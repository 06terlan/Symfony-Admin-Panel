<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileInfoForm extends AbstractType
{
    /**
     * @Assert\Image(
     *     minWidth = 400,
     *     minHeight = 400,
     *     maxSize="10M",
     *     mimeTypes={"image/jpeg", "image/png"}
     * )
     */
    public $image;

    /**
     * @Assert\NotBlank(message = "Name should not be blank")
     */
    public $name;

    public $surname;

    /**
     * @Assert\NotBlank(message = "Login should not be blank")
     */
    public $login;

    /**
     * @Assert\NotBlank(message = "Email should not be blank")
     */
    public $email;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image',FileType::class,['required' => false,'label'=>'Image','attr' => ['accept' => 'image/png, image/jpeg']])
            ->add('name',TextType::class,['label'=>'Name','attr' => ['data-validate-length-range' => '5,100','class' => 'form-control has-feedback-left','placeholder' => 'Name']])
            ->add('surname',TextType::class,['required' => false,'label'=>'Surname','attr' => ['data-validate-length-range' => '5,100','class' => 'form-control has-feedback-left','placeholder' => 'Surname']])
            ->add('login',TextType::class,['label'=>'Login','attr' => ['readonly' => '','class' => 'form-control col-md-7 col-xs-12','placeholder' => 'Login']])
            ->add('email',EmailType::class,['label'=>'Email','attr' => ['class' => 'form-control has-feedback-left','placeholder' => 'Email']])
            ->add('save',SubmitType::class,['label'=>'Save','attr' => ['class' => 'btn btn-success']])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_tooken',
            'csrf_token_id'   => 'form_info'
        ));
    }

    /*public function getBlockPrefix()
    {
        return null;
    }*/

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}