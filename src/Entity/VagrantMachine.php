<?php namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="VSWPG_VagrantMachines")
 */
class VagrantMachine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="machines_group", type="string", length=128)
     * @Assert\NotBlank
     */
    protected $machinesGroup;
    
    /**
     * @ORM\Column(name="name", type="string", length=128)
     * @Assert\NotBlank
     */
    protected $name;
    
    /**
     * @ORM\Column(name="description", type="string", length=128, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(name="ip_address", type="string", length=16)
     * @Assert\NotBlank
     */
    protected $ipAddress;
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getMachinesGroup(): ?string
    {
        return $this->machinesGroup;
    }
    
    public function setMachinesGroup(?string $machinesGroup): self
    {
        $this->machinesGroup = $machinesGroup;
        
        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName( ?string $name ): self
    {
        $this->name  = $name;

        return $this;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }
    
    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        
        return $this;
    }
}
    