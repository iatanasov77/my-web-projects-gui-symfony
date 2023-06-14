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
     * @ORM\Column(name="private_ip_address", type="string", length=16)
     * @Assert\NotBlank
     */
    protected $privateIpAddress;
    
    /**
     * @ORM\Column(name="public_ip_address", type="string", length=16, nullable=true)
     * @Assert\NotBlank
     */
    protected $publicIpAddress;
    
    
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
    
    public function getPrivateIpAddress(): ?string
    {
        return $this->privateIpAddress;
    }
    
    public function setPrivateIpAddress(string $privateIpAddress): self
    {
        $this->privateIpAddress = $privateIpAddress;
        
        return $this;
    }
    
    public function getPublicIpAddress(): ?string
    {
        return $this->publicIpAddress;
    }
    
    public function setPublicIpAddress(string $publicIpAddress): self
    {
        $this->publicIpAddress = $publicIpAddress;
        
        return $this;
    }
}
    