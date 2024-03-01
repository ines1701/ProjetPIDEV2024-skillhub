<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;



#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
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
     * @Assert\NotBlank(message="- categorie ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 4,
     *          minMessage = "- categorie doit avoir minimum 4 caractères."
     * )
     */
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message="- tuteur ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 4,
     *          minMessage = "- tuteur doit avoir minimum 4 caractères."
     * )
     */
    private ?string $tuteur = null;

    #[ORM\Column(length: 255)]
    private ?string $updated = null;

    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'formation')]
    private Collection $ressources;

    #[ORM\Column]
    private ?bool $favoris = false;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
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

    public function getTuteur(): ?string
    {
        return $this->tuteur;
    }

    public function setTuteur(string $tuteur): static
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    public function getUpdated(): ?string
    {
        return $this->updated;
    }

    public function setUpdated(string $updated): static
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setFormation($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getFormation() === $this) {
                $ressource->setFormation(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->ressources;
    }

    public function isFavoris(): ?bool
    {
        return $this->favoris;
    }

    public function setFavoris(bool $favoris): static
    {
        $this->favoris = $favoris;

        return $this;
    }
}
