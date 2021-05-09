<?php namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phpbrew_extensions")
 */
class PhpBrewExtension
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\NotBlank
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     */
    protected $githubRepo;
    
    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     */
    protected $branch;

    public function getId(): ?int
    {
        return $this->id;
    }
    
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
    
    public function getGithubRepo(): ?string
    {
        return $this->githubRepo;
    }
    
    public function setGithubRepo(string $githubRepo): self
    {
        $this->githubRepo = $githubRepo;
        
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
}
