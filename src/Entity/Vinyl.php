<?php

namespace App\Entity;

use App\Repository\VinylRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinylRepository::class)]
class Vinyl implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artist = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prodCountry = null;

    #[ORM\Column(nullable: true)]
    private ?int $prodYear = null;

    #[ORM\Column(nullable: true)]
    private ?bool $signed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'vinyls')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Library $library = null;

    /**
     * @var Collection<int, Bac>
     */
    #[ORM\ManyToMany(targetEntity: Bac::class, mappedBy: 'vinyls')]
    private Collection $bacs;

    public function __construct()
    {
        $this->bacs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getProdCountry(): ?string
    {
        return $this->prodCountry;
    }

    public function setProdCountry(?string $prodCountry): static
    {
        $this->prodCountry = $prodCountry;

        return $this;
    }

    public function getProdYear(): ?int
    {
        return $this->prodYear;
    }

    public function setProdYear(?int $prodYear): static
    {
        $this->prodYear = $prodYear;

        return $this;
    }

    public function isSigned(): ?bool
    {
        return $this->signed;
    }

    public function setSigned(?bool $signed): static
    {
        $this->signed = $signed;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): static
    {
        $this->library = $library;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s — %s', $this->title ?? '', $this->artist ?? '');
    }

    /**
     * @return Collection<int, Bac>
     */
    public function getBacs(): Collection
    {
        return $this->bacs;
    }

    public function addBac(Bac $bac): static
    {
        if (!$this->bacs->contains($bac)) {
            $this->bacs->add($bac);
            $bac->addVinyl($this);
        }

        return $this;
    }

    public function removeBac(Bac $bac): static
    {
        if ($this->bacs->removeElement($bac)) {
            $bac->removeVinyl($this);
        }

        return $this;
    }
}
