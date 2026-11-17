<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Vinyl>
     */
    #[ORM\OneToMany(targetEntity: Vinyl::class, mappedBy: 'library', orphanRemoval: true)]
    private Collection $vinyls;

    #[ORM\OneToOne(mappedBy: 'library', cascade: ['persist', 'remove'])]
    private ?Member $member = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;



    public function __construct()
    {
        $this->vinyls = new ArrayCollection();
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

    /**
     * @return Collection<int, Vinyl>
     */
    public function getVinyls(): Collection
    {
        return $this->vinyls;
    }

    public function addVinyl(Vinyl $vinyl): static
    {
        if (!$this->vinyls->contains($vinyl)) {
            $this->vinyls->add($vinyl);
            $vinyl->setLibrary($this);
        }

        return $this;
    }

    public function removeVinyl(Vinyl $vinyl): static
    {
        if ($this->vinyls->removeElement($vinyl)) {
            // set the owning side to null (unless already changed)
            if ($vinyl->getLibrary() === $this) {
                $vinyl->setLibrary(null);
            }
        }

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): static
    {
        // set the owning side of the relation if necessary
        if ($member->getLibrary() !== $this) {
            $member->setLibrary($this);
        }

        $this->member = $member;

        return $this;
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

    public function __toString(): string
    {
        $vinylsList = implode(', ', $this->vinyls->map(
            fn(Vinyl $vinyl) => (string) $vinyl
        )->toArray());

        return sprintf(
            '<dt>%s</dt><dt>%s</dt><dt>%s</dt>',
            $this->name ?? '',
            $this->member ? (string) $this->member : '',
            $vinylsList !== '' ? $vinylsList : 'Aucun vinyle'
        );
    }




}
