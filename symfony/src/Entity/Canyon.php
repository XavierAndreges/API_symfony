<?php

namespace App\Entity;

use App\Repository\CanyonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CanyonRepository::class)]
#[ORM\Table(name: 'Canyons')]
class Canyon
{
    #[ORM\Id]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $idRepName = null;

    #[ORM\Column]
    private bool $actif = true;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $table = null;

    #[ORM\Column(length: 50)]
    private ?string $title_fr = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $title_en = null;

    #[ORM\Column(nullable: true)]
    private ?string $region = null;

    #[ORM\Column(nullable: true)]
    private ?string $department = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mountains = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $bounds = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $soustype = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $macaron = null;

    #[ORM\Column(nullable: true)]
    private ?string $cotationVerticale = null;

    #[ORM\Column(nullable: true)]
    private ?string $cotationAquatique = null;

    #[ORM\Column(nullable: true)]
    private ?string $cotationEngagement = null;

    #[ORM\Column(nullable: true)]
    private ?int $approcheTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $retourTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $descenteTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $cascadeMax = null;

    #[ORM\Column(nullable: true)]
    private ?int $corde = null;

    #[ORM\Column(nullable: true)]
    private ?int $altitudeDepart = null;

    #[ORM\Column(nullable: true)]
    private ?int $denivele = null;

    #[ORM\Column(nullable: true)]
    private ?int $longueur = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column(nullable: true)]
    private ?int $qualiteEau = null;

    #[ORM\Column(nullable: true)]
    private ?int $frequentation = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitudeRouting = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitudeRouting = null;

    #[ORM\Column(nullable: true)]
    private ?string $traceIGN = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitudeEnd = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitudeEnd = null;

    #[ORM\Column(type: 'text')]
    private ?string $approcheGeoJSON = null;

    #[ORM\Column(type: 'text')]
    private ?string $descenteGeoJSON = null;

    #[ORM\Column(type: 'text')]
    private ?string $retourGeoJSON = null;

    #[ORM\Column(nullable: true)]
    private ?string $smallNodeWalk = null;

    #[ORM\Column(nullable: true)]
    private ?string $smallNodeCar = null;

    #[ORM\Column(nullable: true)]
    private ?string $fullNodeWalk = null;

    #[ORM\Column(nullable: true)]
    private ?string $fullNodeCar = null;

    #[ORM\Column(nullable: true)]
    private ?float $costWalk = null;

    #[ORM\Column(nullable: true)]
    private ?float $costCar = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $closeCity = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $pied = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $velo = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $moto = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $auto = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $bus = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $transport_fr = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $transport_en = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $resume_fr = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(length: 5000, nullable: true)]
    private ?string $text_fr = null;

    #[ORM\Column(length: 5000, nullable: true)]
    private ?string $text_en = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $text_es = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $text_ger = null;

    // Getters and Setters
    public function getIdRepName(): ?string
    {
        return $this->idRepName;
    }

    public function setIdRepName(?string $idRepName): self
    {
        $this->idRepName = $idRepName;
        return $this;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function setTable(?string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(?string $title_fr): self
    {
        $this->title_fr = $title_fr;
        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(?string $title_en): self
    {
        $this->title_en = $title_en;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getMountains(): ?string
    {
        return $this->mountains;
    }

    public function setMountains(?string $mountains): self
    {
        $this->mountains = $mountains;
        return $this;
    }

    public function getBounds(): ?string
    {
        return $this->bounds;
    }

    public function setBounds(?string $bounds): self
    {
        $this->bounds = $bounds;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getSoustype(): ?string
    {
        return $this->soustype;
    }

    public function setSoustype(?string $soustype): self
    {
        $this->soustype = $soustype;
        return $this;
    }

    public function getMacaron(): ?string
    {
        return $this->macaron;
    }

    public function setMacaron(?string $macaron): self
    {
        $this->macaron = $macaron;
        return $this;
    }

    public function getCotationVerticale(): ?string
    {
        return $this->cotationVerticale;
    }

    public function setCotationVerticale(?string $cotationVerticale): self
    {
        $this->cotationVerticale = $cotationVerticale;
        return $this;
    }

    public function getCotationAquatique(): ?string
    {
        return $this->cotationAquatique;
    }

    public function setCotationAquatique(?string $cotationAquatique): self
    {
        $this->cotationAquatique = $cotationAquatique;
        return $this;
    }

    public function getCotationEngagement(): ?string
    {
        return $this->cotationEngagement;
    }

    public function setCotationEngagement(?string $cotationEngagement): self
    {
        $this->cotationEngagement = $cotationEngagement;
        return $this;
    }

    public function getApprocheTime(): ?int
    {
        return $this->approcheTime;
    }

    public function setApprocheTime(?int $approcheTime): self
    {
        $this->approcheTime = $approcheTime;
        return $this;
    }

    public function getRetourTime(): ?int
    {
        return $this->retourTime;
    }

    public function setRetourTime(?int $retourTime): self
    {
        $this->retourTime = $retourTime;
        return $this;
    }

    public function getDescenteTime(): ?int
    {
        return $this->descenteTime;
    }

    public function setDescenteTime(?int $descenteTime): self
    {
        $this->descenteTime = $descenteTime;
        return $this;
    }

    public function getCascadeMax(): ?int
    {
        return $this->cascadeMax;
    }

    public function setCascadeMax(?int $cascadeMax): self
    {
        $this->cascadeMax = $cascadeMax;
        return $this;
    }

    public function getCorde(): ?int
    {
        return $this->corde;
    }

    public function setCorde(?int $corde): self
    {
        $this->corde = $corde;
        return $this;
    }

    public function getAltitudeDepart(): ?int
    {
        return $this->altitudeDepart;
    }

    public function setAltitudeDepart(?int $altitudeDepart): self
    {
        $this->altitudeDepart = $altitudeDepart;
        return $this;
    }

    public function getDenivele(): ?int
    {
        return $this->denivele;
    }

    public function setDenivele(?int $denivele): self
    {
        $this->denivele = $denivele;
        return $this;
    }

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(?int $longueur): self
    {
        $this->longueur = $longueur;
        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getQualiteEau(): ?int
    {
        return $this->qualiteEau;
    }

    public function setQualiteEau(?int $qualiteEau): self
    {
        $this->qualiteEau = $qualiteEau;
        return $this;
    }

    public function getFrequentation(): ?int
    {
        return $this->frequentation;
    }

    public function setFrequentation(?int $frequentation): self
    {
        $this->frequentation = $frequentation;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitudeRouting(): ?float
    {
        return $this->latitudeRouting;
    }

    public function setLatitudeRouting(?float $latitudeRouting): self
    {
        $this->latitudeRouting = $latitudeRouting;
        return $this;
    }

    public function getLongitudeRouting(): ?float
    {
        return $this->longitudeRouting;
    }

    public function setLongitudeRouting(?float $longitudeRouting): self
    {
        $this->longitudeRouting = $longitudeRouting;
        return $this;
    }

    public function getTraceIGN(): ?string
    {
        return $this->traceIGN;
    }

    public function setTraceIGN(?string $traceIGN): self
    {
        $this->traceIGN = $traceIGN;
        return $this;
    }

    public function getLatitudeEnd(): ?float
    {
        return $this->latitudeEnd;
    }

    public function setLatitudeEnd(?float $latitudeEnd): self
    {
        $this->latitudeEnd = $latitudeEnd;
        return $this;
    }

    public function getLongitudeEnd(): ?float
    {
        return $this->longitudeEnd;
    }

    public function setLongitudeEnd(?float $longitudeEnd): self
    {
        $this->longitudeEnd = $longitudeEnd;
        return $this;
    }

    public function getApprocheGeoJSON(): ?string
    {
        return $this->approcheGeoJSON;
    }

    public function setApprocheGeoJSON(?string $approcheGeoJSON): self
    {
        $this->approcheGeoJSON = $approcheGeoJSON;
        return $this;
    }

    public function getDescenteGeoJSON(): ?string
    {
        return $this->descenteGeoJSON;
    }

    public function setDescenteGeoJSON(?string $descenteGeoJSON): self
    {
        $this->descenteGeoJSON = $descenteGeoJSON;
        return $this;
    }

    public function getRetourGeoJSON(): ?string
    {
        return $this->retourGeoJSON;
    }

    public function setRetourGeoJSON(?string $retourGeoJSON): self
    {
        $this->retourGeoJSON = $retourGeoJSON;
        return $this;
    }

    public function getSmallNodeWalk(): ?string
    {
        return $this->smallNodeWalk;
    }

    public function setSmallNodeWalk(?string $smallNodeWalk): self
    {
        $this->smallNodeWalk = $smallNodeWalk;
        return $this;
    }

    public function getSmallNodeCar(): ?string
    {
        return $this->smallNodeCar;
    }

    public function setSmallNodeCar(?string $smallNodeCar): self
    {
        $this->smallNodeCar = $smallNodeCar;
        return $this;
    }

    public function getFullNodeWalk(): ?string
    {
        return $this->fullNodeWalk;
    }

    public function setFullNodeWalk(?string $fullNodeWalk): self
    {
        $this->fullNodeWalk = $fullNodeWalk;
        return $this;
    }

    public function getFullNodeCar(): ?string
    {
        return $this->fullNodeCar;
    }

    public function setFullNodeCar(?string $fullNodeCar): self
    {
        $this->fullNodeCar = $fullNodeCar;
        return $this;
    }

    public function getCostWalk(): ?float
    {
        return $this->costWalk;
    }

    public function setCostWalk(?float $costWalk): self
    {
        $this->costWalk = $costWalk;
        return $this;
    }

    public function getCostCar(): ?float
    {
        return $this->costCar;
    }

    public function setCostCar(?float $costCar): self
    {
        $this->costCar = $costCar;
        return $this;
    }

    public function getCloseCity(): ?string
    {
        return $this->closeCity;
    }

    public function setCloseCity(?string $closeCity): self
    {
        $this->closeCity = $closeCity;
        return $this;
    }

    public function getPied(): ?string
    {
        return $this->pied;
    }

    public function setPied(?string $pied): self
    {
        $this->pied = $pied;
        return $this;
    }

    public function getVelo(): ?string
    {
        return $this->velo;
    }

    public function setVelo(?string $velo): self
    {
        $this->velo = $velo;
        return $this;
    }

    public function getMoto(): ?string
    {
        return $this->moto;
    }

    public function setMoto(?string $moto): self
    {
        $this->moto = $moto;
        return $this;
    }

    public function getAuto(): ?string
    {
        return $this->auto;
    }

    public function setAuto(?string $auto): self
    {
        $this->auto = $auto;
        return $this;
    }

    public function getBus(): ?string
    {
        return $this->bus;
    }

    public function setBus(?string $bus): self
    {
        $this->bus = $bus;
        return $this;
    }

    public function getTransportFr(): ?string
    {
        return $this->transport_fr;
    }

    public function setTransportFr(?string $transport_fr): self
    {
        $this->transport_fr = $transport_fr;
        return $this;
    }

    public function getTransportEn(): ?string
    {
        return $this->transport_en;
    }

    public function setTransportEn(?string $transport_en): self
    {
        $this->transport_en = $transport_en;
        return $this;
    }

    public function getResumeFr(): ?string
    {
        return $this->resume_fr;
    }

    public function setResumeFr(?string $resume_fr): self
    {
        $this->resume_fr = $resume_fr;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    public function getTextFr(): ?string
    {
        return $this->text_fr;
    }

    public function setTextFr(?string $text_fr): self
    {
        $this->text_fr = $text_fr;
        return $this;
    }

    public function getTextEn(): ?string
    {
        return $this->text_en;
    }

    public function setTextEn(?string $text_en): self
    {
        $this->text_en = $text_en;
        return $this;
    }

    public function getTextEs(): ?string
    {
        return $this->text_es;
    }

    public function setTextEs(?string $text_es): self
    {
        $this->text_es = $text_es;
        return $this;
    }

    public function getTextGer(): ?string
    {
        return $this->text_ger;
    }

    public function setTextGer(?string $text_ger): self
    {
        $this->text_ger = $text_ger;
        return $this;
    }
}
