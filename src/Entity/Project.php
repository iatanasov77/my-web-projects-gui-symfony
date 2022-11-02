<?php namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="VSWPG_Projects")
 */
class Project
{
    public function __construct()
    {
        $this->hosts    = new ArrayCollection();
    }
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="projects")
     */
    protected $category;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectHost", mappedBy="project", cascade={"remove"})
     */
    protected $hosts;
    
    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     */
    protected $name;
    
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    
    /**
     * @ORM\Column(name="source_type", type="string", columnDefinition="enum('wget', 'git', 'svn', 'install_manual')")
     */
    protected $sourceType;
    
    
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $repository;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $branch;
    
    /**
     * @ORM\Column(name="project_root", type="string", length=128)
     * @Assert\NotBlank
     */
    protected $projectRoot;
    
    /**
     * @ORM\Column(name="install_manual", type="string")
     */
    protected $installManual;
    
    /**
     * @ORM\Column(name="predefinedType", type="string", length=64)
     */
    protected $predefinedType;
    
    /**
     * @ORM\Column(name="predefinedTypeParams", type="json")
     */
    protected $predefinedTypeParams;
    
    /**
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $projectUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }
    
    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        
        return $this;
    }
    
    /**
     * @return Collection|ProjectHost[]
     */
    public function getHosts(): Collection
    {
        return $this->hosts;
    }
    // addHost() and removeHosts() were also should added
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }

    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setSourceType(string $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getProjectRoot(): ?string
    {
        return $this->projectRoot;
    }

    public function setProjectRoot(string $projectRoot): self
    {
        $this->projectRoot = $projectRoot;

        return $this;
    }
    
    public function getInstallManual(): ?string
    {
        return $this->installManual;
    }
    
    public function setInstallManual(string $installManual): self
    {
        $this->installManual = $installManual;
        
        return $this;
    }
    
    public function getPredefinedType(): ?string
    {
        return $this->predefinedType;
    }
    
    public function setPredefinedType(string $predefinedType): self
    {
        $this->predefinedType = $predefinedType;
        
        return $this;
    }
    
    public function getPredefinedTypeParams(): ?array
    {
        return $this->predefinedTypeParams;
    }
    
    public function setPredefinedTypeParams(?array $predefinedTypeParams): self
    {
        $this->predefinedTypeParams = $predefinedTypeParams;
        
        return $this;
    }
    
    public function getProjectUrl(): ?string
    {
        return $this->projectUrl;
    }
    
    public function setProjectUrl(string $projectUrl): self
    {
        $this->projectUrl   = $projectUrl;
        
        return $this;
    }
}
