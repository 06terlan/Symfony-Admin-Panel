<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserGroup
 *
 * @ORM\Table(name="user_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserGroupRepository")
 * @UniqueEntity(fields={"groupKey"}, groups={"insertion"}, message="Group Key looks like already exist!")
 */
class UserGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="group_key", type="string", length=20, unique=true)
     *
     * @Assert\NotBlank(message = "Group Key should not be blank", groups={"insertion"})
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Group Key must be at least {{ limit }} characters long",
     *      maxMessage = "Group Key cannot be longer than {{ limit }} characters",
     *      groups={"insertion"}
     * )
     */
    private $groupKey;

    /**
     * @var string
     *
     * @ORM\Column(name="group_name", type="string", length=50)
     *
     * @Assert\NotBlank(message = "Group name should not be blank", groups={"insertion"})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Group name must be at least {{ limit }} characters long",
     *      maxMessage = "Group name cannot be longer than {{ limit }} characters",
     *      groups={"insertion"}
     * )
     */
    private $groupName;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set groupKey
     *
     * @param string $groupKey
     *
     * @return UserGroup
     */
    public function setGroupKey($groupKey)
    {
        $this->groupKey = $groupKey;

        return $this;
    }

    /**
     * Get groupKey
     *
     * @return string
     */
    public function getGroupKey()
    {
        return $this->groupKey;
    }

    /**
     * Set groupName
     *
     * @param string $groupName
     *
     * @return UserGroup
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Get groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }
}
