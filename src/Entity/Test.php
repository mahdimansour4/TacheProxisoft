<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEcheance = null;

    #[ORM\Column]
    private ?int $devis = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $tags = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $source = [];

    #[ORM\Column]
    private ?int $estimation = null;

    /**
     * @var Collection<int, Activite>
     */
    #[ORM\ManyToMany(targetEntity: Activite::class, mappedBy: 'test')]
    private Collection $activites;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $tagColors = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $sourceColors = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'Test')]
    private Collection $tags2;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $tags4 = null;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->tags2 = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateEcheance(): ?\DateTimeInterface
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeInterface $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getDevis(): ?int
    {
        return $this->devis;
    }

    public function setDevis(int $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSource(): array
    {
        return $this->source;
    }

    public function setSource(array $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getEstimation(): ?int
    {
        return $this->estimation;
    }

    public function setEstimation(int $estimation): static
    {
        $this->estimation = $estimation;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->addTest($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            $activite->removeTest($this);
        }

        return $this;
    }

    public function getTagColors(): ?array
    {
        return $this->tagColors;
    }

    public function setTagColors(?array $tagColors): static
    {
        $this->tagColors = $tagColors;

        return $this;
    }

    public function getSourceColors(): ?array
    {
        return $this->sourceColors;
    }

    public function setSourceColors(?array $sourceColors): static
    {
        $this->sourceColors = $sourceColors;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags2(): Collection
    {
        return $this->tags2;
    }

    public function addTags2(Tag $tags2): static
    {
        if (!$this->tags2->contains($tags2)) {
            $this->tags2->add($tags2);
            $tags2->addTest($this);
        }

        return $this;
    }

    public function removeTags2(Tag $tags2): static
    {
        if ($this->tags2->removeElement($tags2)) {
            $tags2->removeTest($this);
        }

        return $this;
    }

    public function getTags4(): ?array
    {
        return $this->tags4;
    }

    public function setTags4(?array $tags4): static
    {
        $this->tags4 = $tags4;

        return $this;
    }



}
