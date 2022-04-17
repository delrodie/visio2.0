<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantHt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remise;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tva;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantTTC;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accompte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rap;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partAssurance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odcyl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odAxe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odAdd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odQte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $odMontant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odSph;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogCyl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogAxe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogAdd;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ogQte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ogMontant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogSph;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prixMonture;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Monture::class)
     */
    private $monture;

    /**
     * @ORM\ManyToOne(targetEntity=TypeVerre::class)
     */
    private $typeVerre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Assurance::class)
     */
    private $assurance;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $montureBool;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verreBool;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getMontantHt(): ?int
    {
        return $this->montantHt;
    }

    public function setMontantHt(?int $montantHt): self
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    public function getRemise(): ?int
    {
        return $this->remise;
    }

    public function setRemise(?int $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(?int $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getMontantTTC(): ?int
    {
        return $this->montantTTC;
    }

    public function setMontantTTC(?int $montantTTC): self
    {
        $this->montantTTC = $montantTTC;

        return $this;
    }

    public function getAccompte(): ?int
    {
        return $this->accompte;
    }

    public function setAccompte(?int $accompte): self
    {
        $this->accompte = $accompte;

        return $this;
    }

    public function getRap(): ?int
    {
        return $this->rap;
    }

    public function setRap(?int $rap): self
    {
        $this->rap = $rap;

        return $this;
    }

    public function getPartAssurance(): ?int
    {
        return $this->partAssurance;
    }

    public function setPartAssurance(?int $partAssurance): self
    {
        $this->partAssurance = $partAssurance;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOdcyl(): ?string
    {
        return $this->odcyl;
    }

    public function setOdcyl(?string $odcyl): self
    {
        $this->odcyl = $odcyl;

        return $this;
    }

    public function getOdAxe(): ?string
    {
        return $this->odAxe;
    }

    public function setOdAxe(?string $odAxe): self
    {
        $this->odAxe = $odAxe;

        return $this;
    }

    public function getOdAdd(): ?string
    {
        return $this->odAdd;
    }

    public function setOdAdd(?string $odAdd): self
    {
        $this->odAdd = $odAdd;

        return $this;
    }

    public function getOdQte(): ?string
    {
        return $this->odQte;
    }

    public function setOdQte(?string $odQte): self
    {
        $this->odQte = $odQte;

        return $this;
    }

    public function getOdMontant(): ?int
    {
        return $this->odMontant;
    }

    public function setOdMontant(?int $odMontant): self
    {
        $this->odMontant = $odMontant;

        return $this;
    }

    public function getOdSph(): ?string
    {
        return $this->odSph;
    }

    public function setOdSph(?string $odSph): self
    {
        $this->odSph = $odSph;

        return $this;
    }

    public function getOgCyl(): ?string
    {
        return $this->ogCyl;
    }

    public function setOgCyl(?string $ogCyl): self
    {
        $this->ogCyl = $ogCyl;

        return $this;
    }

    public function getOgAxe(): ?string
    {
        return $this->ogAxe;
    }

    public function setOgAxe(?string $ogAxe): self
    {
        $this->ogAxe = $ogAxe;

        return $this;
    }

    public function getOgAdd(): ?string
    {
        return $this->ogAdd;
    }

    public function setOgAdd(?string $ogAdd): self
    {
        $this->ogAdd = $ogAdd;

        return $this;
    }

    public function getOgQte(): ?int
    {
        return $this->ogQte;
    }

    public function setOgQte(?int $ogQte): self
    {
        $this->ogQte = $ogQte;

        return $this;
    }

    public function getOgMontant(): ?int
    {
        return $this->ogMontant;
    }

    public function setOgMontant(?int $ogMontant): self
    {
        $this->ogMontant = $ogMontant;

        return $this;
    }

    public function getOgSph(): ?string
    {
        return $this->ogSph;
    }

    public function setOgSph(?string $ogSph): self
    {
        $this->ogSph = $ogSph;

        return $this;
    }

    public function getPrixMonture(): ?int
    {
        return $this->prixMonture;
    }

    public function setPrixMonture(?int $prixMonture): self
    {
        $this->prixMonture = $prixMonture;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getMonture(): ?Monture
    {
        return $this->monture;
    }

    public function setMonture(?Monture $monture): self
    {
        $this->monture = $monture;

        return $this;
    }

    public function getTypeVerre(): ?TypeVerre
    {
        return $this->typeVerre;
    }

    public function setTypeVerre(?TypeVerre $typeVerre): self
    {
        $this->typeVerre = $typeVerre;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
	
	/**
	 * @ORM\PrePersist()
	 */
	public function setCreatedAtValue(): \DateTime
                                    	{
                                    		return $this->createdAt = new \DateTime();
                                    	}
	
	/**
	 * @ORM\PreUpdate()
	 */
	public function setUpdatedAtValue(): \DateTime
                                    	{
                                    		return $this->updatedAt = new \DateTime();
                                    	}

    public function getAssurance(): ?Assurance
    {
        return $this->assurance;
    }

    public function setAssurance(?Assurance $assurance): self
    {
        $this->assurance = $assurance;

        return $this;
    }

    public function getMontureBool(): ?bool
    {
        return $this->montureBool;
    }

    public function setMontureBool(?bool $montureBool): self
    {
        $this->montureBool = $montureBool;

        return $this;
    }

    public function getVerreBool(): ?bool
    {
        return $this->verreBool;
    }

    public function setVerreBool(?bool $verreBool): self
    {
        $this->verreBool = $verreBool;

        return $this;
    }
}
