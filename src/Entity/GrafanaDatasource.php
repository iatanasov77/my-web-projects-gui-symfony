<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "VSWPG_GrafanaDatasources")]
class GrafanaDatasource
{
    /** @var int */
    #[ORM\Id, ORM\Column(type: "integer"), ORM\GeneratedValue(strategy: "IDENTITY")]
    protected $id;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 128)]
    protected $name;
    
    /** @var string */
    #[ORM\Column(type: "text", nullable: true)]
    protected $description;
    
    /** @var string */
    #[ORM\Column(type: "string", length: 128, nullable: true)]
    protected $link;
    
    /** @var ProjectHost[] */
    #[ORM\OneToMany(targetEntity: "GrafanaDatasourceExporter", mappedBy: "datasource", cascade: ["all"], orphanRemoval: true)]
    protected $exporters;
    
    public function __construct()
    {
        $this->exporters = new ArrayCollection();
    }
    
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
    
    public function getLink(): ?string
    {
        return $this->link;
    }
    
    public function setLink(string $link): self
    {
        $this->link = $link;
        
        return $this;
    }
    
    public function getExporters(): Collection
    {
        return $this->exporters;
    }
    
    public function addExporter( GrafanaDatasourceExporter $exporter ): self
    {
        if ( ! $this->exporters->contains( $exporter ) ) {
            $this->exporters[] = $exporter;
        }
        
        return $this;
    }
    
    public function removeExporter( GrafanaDatasourceExporter $exporter ): self
    {
        if ( $this->exporters->contains( $exporter ) ) {
            $this->exporters->removeElement( $exporter );
        }
        
        return $this;
    }
}