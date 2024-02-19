<?php

namespace App\Entity;

use App\Repository\CondidatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

#[ORM\Entity(repositoryClass: CondidatureRepository::class)]
class Condidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]/**
    * @Assert\NotBlank(message="Le nom ne peut pas etre vide.")
    * @Assert\Length(
    *          min = 4,
    *          minMessage = "Le nom doit avoir minimum 4 caractères."
    * )
    */
    private ?string $name = null;

    #[ORM\Column(length: 255)]/**
    * @Assert\NotBlank(message="Le prénom ne peut pas etre vide.")
    * @Assert\Length(
    *          min = 3,
    *          minMessage = "Le prénom doit avoir minimum 3 caractères."
    * )
    */
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="L'Email ne peut pas etre vide.")
     * @Assert\Email(message="L'Email {{ value }} n'est pas valide."
     * )
     */
    private ?string $email = null;

    #[ORM\Column]/**
    * @Assert\NotBlank(message="Le numéro ne peut pas etre vide.")
    * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only") 
    */
    private ?int $numTel = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message="La lettre de motivation ne peut pas etre vide.")
     * )
     */
    private ?string $lettremotivation = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Le CV ne peut pas etre vide.")
     * )
     */
    private ?string $cv = null;


    #[ORM\ManyToOne(inversedBy: 'condidatures')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id',nullable: false)]
    private ?Project $project;

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $project_id = null;

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): static
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getLettremotivation(): ?string
    {
        return $this->lettremotivation;
    }

    public function setLettremotivation(string $lettremotivation): static
    {
        $this->lettremotivation = $lettremotivation;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $projectId): self
    {
        $this->project_id = $projectId;
        return $this;
    }
}
