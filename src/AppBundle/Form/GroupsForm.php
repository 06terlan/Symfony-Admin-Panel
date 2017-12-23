<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupsForm extends AbstractType
{
    public $groupName;
    public $groupKey;


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupName',TextType::class)
            ->add('groupKey',TextType::class)
            ->add('save',SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST',
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_tooken',
            'csrf_token_id'   => 'form_group',
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