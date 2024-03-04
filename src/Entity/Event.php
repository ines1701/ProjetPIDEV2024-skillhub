<?php

namespace App\Entity;


use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 /**
     * @Assert\NotBlank(message=" Le titre ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 5,
     *          minMessage = "Le titre doit avoir minimum 5 caractères."
     * )
     */

    #[ORM\Column(length: 255)]
    private ?string $titre = null;
 /**
     * @Assert\NotBlank(message=" La description ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 25,
     *          minMessage = "La description doit avoir minimum 25 caractères."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $describ = null;

 /**
     * @Assert\NotBlank(message=" Le lieu ne peut pas etre vide.")
     * @Assert\Length(
     *          min = 5,
     *          minMessage = "Le lieu doit avoir minimum 5 caractères."
     * )
     */
    #[ORM\Column(length: 255)]
    private ?string $lieu = null;



     /**
    
     * @Assert\GreaterThanOrEqual(
     *      "today",
     *      message="La date doit être égale ou postérieure à aujourd'hui."
     * )
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

/**
     * @var UploadedFile|null
     */
    private $imageFile;

    #[ORM\Column(length: 255)]
    private ?string $video = null;


    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEvent $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $view = 0;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Inscrip::class)]
    private Collection $inscription;

    public function __construct()
    {
        $this->inscription = new ArrayCollection();
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

    public function getDescrib(): ?string
    {
        return $this->describ;
    }

    public function setDescrib(string $describ): static
    {
        $this->describ = $describ;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }



    public function getType(): ?TypeEvent
    {
        return $this->type;
    }

    public function setType(?TypeEvent $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(?int $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function incrementViews(): self
    {
        $this->view++;

        return $this;
    }

    /**
     * @return Collection<int, Inscrip>
     */
    public function getInscription(): Collection
    {
        return $this->inscription;
    }

    public function addInscription(Inscrip $inscription): static
    {
        if (!$this->inscription->contains($inscription)) {
            $this->inscription->add($inscription);
            $inscription->setEvent($this);
        }

        return $this;
    }

    public function removeInscription(Inscrip $inscription): static
    {
        if ($this->inscription->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getEvent() === $this) {
                $inscription->setEvent(null);
            }
        }

        return $this;
    }
   
 
  

}
