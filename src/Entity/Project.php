<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Le titre ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 4,
     *          minMessage = "Le titre doit avoir minimum 4 caractères."
     * )
     */
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="La catégorie ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 4,
     *          minMessage = "La catégorie doit avoir minimum 4 caractères."
     * )
     */
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $periode = null;

    #[ORM\Column(length: 255)]
    private ?string $portee = null;

    #[ORM\Column(length: 255)]/**
    * @Assert\NotBlank(message="La discription ne peut pas etre vide.")
    * @Assert\Length(
    *          min = 10,
    *          minMessage = "La discription doit avoir minimum 10 caractères."
    * )
    */
    private ?string $description = null;

    #[ORM\Column]/**
    * @Assert\NotBlank(message="Le budget ne peut pas etre vide.")
    * @Assert\Length(
    *          min = 3,
    *           max = 10,
    *          minMessage = "Le numéro doit avoir minimum 3 chiffres.",
    *           maxMessage = "Le numéro ne peut pas passer 10 chiffres."
    * )
    * @Assert\Regex(pattern="/^[0-9]*$/", message="Que des chiffres positives.") 
    */
    private ?float $budget = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Condidature::class)]
    private Collection $condidatures;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAT = null;



    public function __construct()
    {
        $this->condidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): static
    {
        $this->periode = $periode;

        return $this;
    }

    public function getPortee(): ?string
    {
        return $this->portee;
    }

    public function setPortee(string $portee): static
    {
        $this->portee = $portee;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getCondidatures(): Collection
    {
        return $this->condidatures;
    }

    public function addCondidature(Condidature $condidature): self
    {
        if (!$this->condidatures->contains($condidature)) {
            $this->condidatures[] = $condidature;
            $condidature->setProject($this);
        }

        return $this;
    }

    public function removeCondidature(Condidature $condidature): self
    {
        if ($this->condidatures->removeElement($condidature)) {
            // set the owning side to null to disassociate the condidature from the project
            if ($condidature->getProject() === $this) {
                $condidature->setProject(null);
            }
        }

        return $this;
    }

    public function getCreatedAT(): ?\DateTimeInterface
    {
        return $this->createdAT;
    }

    public function setCreatedAT(\DateTimeInterface $createdAT): static
    {
        $this->createdAT = $createdAT;

        return $this;
    }



}
