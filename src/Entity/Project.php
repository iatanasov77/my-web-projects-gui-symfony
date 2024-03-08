<?php namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProjectRepository;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: "VSWPG_Projects")]
class Project
{
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    protected $id;
    
    /** @var Category */
    #[ORM\ManyToOne(targetEntity: "Category", inversedBy: "projects", cascade: ["all"], fetch: "EAGER")]
    protected $category;
    
    /** @var ProjectHost[] */
    #[ORM\OneToMany(targetEntity: "ProjectHost", mappedBy: "project", cascade: ["all"], orphanRemoval: true)]
    protected $hosts;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 128)]
    #[Assert\NotBlank]
    protected $name;
    
    /** @var string */
    #[ORM\Column(type: "text", nullable: true)]
    protected $description;
    
    /** @var string */
    #[ORM\Column(name: "source_type", type: "string", columnDefinition: "enum('wget', 'git', 'svn', 'install_manual')", nullable: true)]
    protected $sourceType;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 128, nullable: true)]
    protected $repository;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 32, nullable: true)]
    protected $branch;
    
    /** @var string */
    #[ORM\Column(name: "project_root", type: "string", length: 128)]
    #[Assert\NotBlank]
    protected $projectRoot;
    
    /** @var string */
    #[ORM\Column(name: "install_manual", type: "string", nullable: true)]
    protected $installManual;
    
    /** @var string */
    #[ORM\Column(name: "predefinedType", type: "string", length: 64, nullable: true)]
    protected $predefinedType;
    
    /** @var array */
    #[ORM\Column(name: "predefinedTypeParams", type: "json", nullable: true)]
    protected $predefinedTypeParams;
    
    /** @var string */
    #[ORM\Column(name: "url", type: "string", length: 255, nullable: true)]
    protected $projectUrl;
    
    public function __construct()
    {
        $this->hosts    = new ArrayCollection();
    }

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
