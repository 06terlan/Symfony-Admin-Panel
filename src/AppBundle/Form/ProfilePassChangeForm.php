<?php
namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class ProfilePassChangeForm extends AbstractType
{
    private $user;
    private $encoder;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->encoder = new BCryptPasswordEncoder(4);
    }

    /**
     * @Assert\NotBlank(message = "Old password should not be blank")
     */
    public $old_password;

    /**
     * @Assert\NotBlank(message = "Password should not be blank")
     */
    public $password;

    /**
     * @Assert\NotBlank(message = "Repassword should not be blank")
     */
    public $re_password;

    /**
     * @Assert\IsTrue(message = "The password cannot match")
     */
    public function isPasswordMatch()
    {
        return $this->getPassword() === $this->getRePassword();
    }


    /**
     * @Assert\IsTrue(message = "The password is not true")
     */
    public function isPasswordTrue()
    {
        return $this->encoder->isPasswordValid($this->user->getPassword(), $this->getPassword(), $this->user->getSalt());
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password',PasswordType::class,['attr' => ['class' => 'form-control col-md-7 col-xs-12','placeholder' => 'Old Password']])
            ->add('password',PasswordType::class,['attr' => ['class' => 'form-control col-md-7 col-xs-12','placeholder' => 'Password','id' => 'password']])
            ->add('re_password',PasswordType::class,['attr' => ['class' => 'form-control col-md-7 col-xs-12','placeholder' => 'Repeat Password'/*,'data-validate-linked' => 'profile_pass_change_form_password'*/],'label'  => 'Repeat Password'])
            ->add('save',SubmitType::class,['label'=>'Change Password','attr' => ['class' => 'btn btn-success']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
            'csrf_protection' => false,
            'csrf_field_name' => '_csrf_tooken',
            'intention'       => 'form_change_pass'
        ));
    }

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->old_password;
    }

    /**
     * @param mixed $old_password
     */
    public function setOldPassword($old_password)
    {
        $this->old_password = $old_password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRePassword()
    {
        return $this->re_password;
    }

    /**
     * @param mixed $re_password
     */
    public function setRePassword($re_password)
    {
        $this->re_password = $re_password;
    }
}