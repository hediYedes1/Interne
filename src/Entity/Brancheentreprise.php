<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Partenariat;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BrancheentrepriseRepository;

#[ORM\Entity(repositoryClass: BrancheentrepriseRepository::class)]
class Brancheentreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombranche = null;

    #[ORM\Column(length: 255)]
    private ?string $adressebranche = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $longitude = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'identreprise', referencedColumnName: 'identreprise', onDelete: 'CASCADE')]
    private ?Entreprise $identreprise = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: "brancheentreprises")]
    #[ORM\JoinColumn(name: 'idutilisateur', referencedColumnName: 'idutilisateur', onDelete: 'CASCADE')]
    private Utilisateur $idutilisateur;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "La localisation de la branche est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La localisation ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $localisationbranche;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "L'email de la branche est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9._%+-]+@esprit\.tn$/",
        message: "L'email doit être au format 'nom.prenom@esprit.tn'."
    )]
    private string $emailbranche;

    #[ORM\Column(type: "string", length: 15)]
    #[Assert\NotBlank(message: "Le contact de la branche est obligatoire.")]
    #[Assert\Length(
        max: 15,
        maxMessage: "Le contact ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^[0-9\-\+]+$/",
        message: "Le contact doit contenir uniquement des chiffres, des tirets ou des signes '+'"
    )]
    private string $contactbranche;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "Le nombre d'employés est obligatoire.")]
    #[Assert\Positive(message: "Le nombre d'employés doit être un nombre positif.")]
    private int $nombreemploye;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Le responsable de la branche est obligatoire.")]
    #[Assert\Length(
        max: 100,
        maxMessage: "Le nom du responsable ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $responsablebranche;

    #[ORM\OneToMany(mappedBy: "idbranche", targetEntity: Partenariat::class)]
    private Collection $partenariats;

    public function __construct()
    {
        $this->partenariats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombranche(): ?string
    {
        return $this->nombranche;
    }

    public function setNombranche(string $nombranche): static
    {
        $this->nombranche = $nombranche;
        return $this;
    }

    public function getAdressebranche(): ?string
    {
        return $this->adressebranche;
    }

    public function setAdressebranche(string $adressebranche): static
    {
        $this->adressebranche = $adressebranche;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getIdentreprise(): ?Entreprise
    {
        return $this->identreprise;
    }

    public function setIdentreprise(?Entreprise $identreprise): self
    {
        $this->identreprise = $identreprise;
        return $this;
    }

    public function getIdutilisateur(): Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(Utilisateur $idutilisateur): self
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }

    public function getLocalisationbranche(): string
    {
        return $this->localisationbranche;
    }

    public function setLocalisationbranche(string $localisationbranche): self
    {
        $this->localisationbranche = $localisationbranche;

        return $this;
    }

    public function getEmailbranche(): string
    {
        return $this->emailbranche;
    }

    public function setEmailbranche(string $emailbranche): self
    {
        $this->emailbranche = $emailbranche;

        return $this;
    }

    public function getContactbranche(): string
    {
        return $this->contactbranche;
    }

    public function setContactbranche(string $contactbranche): self
    {
        $this->contactbranche = $contactbranche;

        return $this;
    }

    public function getNombreemploye(): int
    {
        return $this->nombreemploye;
    }

    public function setNombreemploye(int $nombreemploye): self
    {
        $this->nombreemploye = $nombreemploye;

        return $this;
    }

    public function getResponsablebranche(): string
    {
        return $this->responsablebranche;
    }

    public function setResponsablebranche(string $responsablebranche): self
    {
        $this->responsablebranche = $responsablebranche;

        return $this;
    }

    public function getPartenariats(): Collection
    {
        return $this->partenariats;
    }

    public function addPartenariat(Partenariat $partenariat): self
    {
        if (!$this->partenariats->contains($partenariat)) {
            $this->partenariats[] = $partenariat;
            $partenariat->setIdbranche($this);
        }

        return $this;
    }

    public function removePartenariat(Partenariat $partenariat): self
    {
        if ($this->partenariats->removeElement($partenariat)) {
            if ($partenariat->getIdbranche() === $this) {
                $partenariat->setIdbranche(null);
            }
        }

        return $this;
    }
} 