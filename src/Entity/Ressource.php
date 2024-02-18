<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;



#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

     /**
     * @Assert\NotBlank(message="- Le titre ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 4,
     *          minMessage = "- Le titre doit avoir minimum 4 caractères."
     * )
     */
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message="- La description ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 10,
     *          minMessage = "- Le titre doit avoir minimum 10 caractères."
     * )
     */
    private ?string $description = null;

    #[ORM\Column(type: 'string')]
    private ?string $file;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

   
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre)
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }




    public function getFilename(): string
    {
        return $this->file;
    }

    public function setFilename(string $file): self
    {
        $this->file = $file;

        return $this;
    }
    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file)
    {
        $this->file= $file;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }
}