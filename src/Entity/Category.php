<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\CategoryRepository;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: "VSWPG_Categories")]
class Category
{
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    protected $id;

    /** @var string */
    #[ORM\Column(type: "string")]
    protected $name;
    
    /** @var Project[] */
    #[ORM\OneToMany(targetEntity: "Project", mappedBy: "category")]
    protected $projects;
    
    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }
    
    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
}
