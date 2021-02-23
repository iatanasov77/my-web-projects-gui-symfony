<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use VS\UsersBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="VSUM_Users")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserManagement\UserActivity", mappedBy="user")
     */
    protected $activities;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserManagement\UserNotification", mappedBy="user")
     */
    protected $notifications;
    
    /**
     * @return Collection|UserActivity[]
     */
    public function getActivities()
    {
        return $this->activities;
    }
    
    /**
     * @return Collection|UserActivity[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
