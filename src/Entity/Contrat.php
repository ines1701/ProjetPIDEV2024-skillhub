<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
/**
 * @Assert\NotBlank(message="Le champ  ne doit pas être vide.")
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z]*$/",
 *     message="Le champ doit contenir uniquement des lettres."
 * )
 */
    #[ORM\Column(length: 255)]
    private ?string $nom_client = null;
/**
 * @Assert\NotBlank(message="Le champ  ne doit pas être vide.")
 */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_contrat = null;
/**
 * @Assert\NotBlank(message="Le champ  ne doit pas être vide.")
 * @Assert\PositiveOrZero(message="Le champ ne doit pas être négatif.")
 */
    #[ORM\Column]
    private ?float $montant = null;
/**
 * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
 */
    #[ORM\Column(length: 255)]
    private ?string $description = null;
/**
 * @Assert\NotBlank(message="Le champ ne doit pas être vide.")
 */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nom_client;
    }

    public function setNomClient(string $nom_client): static
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getDateContrat(): ?\DateTimeInterface
    {
        return $this->date_contrat;
    }

    public function setDateContrat(\DateTimeInterface $date_contrat): static
    {
        $this->date_contrat = $date_contrat;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

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
    public function __toString() {
        return $this->getId(); // or any other logic to represent the object as a string
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
    public function __construct()
    {
        // Set $datecom to the current date when the object is created
        $this->date_contrat = new \DateTime();
    }
}
