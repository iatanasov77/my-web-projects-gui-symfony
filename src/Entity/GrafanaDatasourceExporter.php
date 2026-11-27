<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "VSWPG_GrafanaDatasourceExporters")]
class GrafanaDatasourceExporter
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
    
    /** @var Project */
    #[ORM\ManyToOne(targetEntity: "GrafanaDatasource", inversedBy: "exporters")]
    protected $datasource;
    
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
    
    public function getDatasource(): ?GrafanaDatasource
    {
        return $this->datasource;
    }
    
    public function setDatasource(GrafanaDatasource $datasource): self
    {
        $this->datasource = $datasource;
        
        return $this;
    }
}