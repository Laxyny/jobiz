<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?bool $remote_allowed = null;

    #[ORM\Column]
    private ?int $salary_range_min = null;

    #[ORM\Column]
    private ?float $salary_range_max = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?JobType $jobtype = null;

    /**
     * @var Collection<int, JobCategory>
     */
    #[ORM\ManyToMany(targetEntity: JobCategory::class, inversedBy: 'jobs')]
    private Collection $jobcategorys;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?Company $company = null;

    /**
     * @var Collection<int, JobApplication>
     */
    #[ORM\OneToMany(targetEntity: JobApplication::class, mappedBy: 'Job')]
    private Collection $jobApplications;

    public function __construct()
    {
        $this->jobcategorys = new ArrayCollection();
        $this->jobApplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function isRemoteAllowed(): ?bool
    {
        return $this->remote_allowed;
    }

    public function setRemoteAllowed(bool $remote_allowed): static
    {
        $this->remote_allowed = $remote_allowed;

        return $this;
    }

    public function getSalaryRangeMin(): ?int
    {
        return $this->salary_range_min;
    }

    public function setSalaryRangeMin(int $salary_range_min): static
    {
        $this->salary_range_min = $salary_range_min;

        return $this;
    }

    public function getSalaryRangeMax(): ?float
    {
        return $this->salary_range_max;
    }

    public function setSalaryRangeMax(float $salary_range_max): static
    {
        $this->salary_range_max = $salary_range_max;

        return $this;
    }

    public function getJobtype(): ?JobType
    {
        return $this->jobtype;
    }

    public function setJobtype(?JobType $jobtype): static
    {
        $this->jobtype = $jobtype;

        return $this;
    }

    /**
     * @return Collection<int, JobCategory>
     */
    public function getJobcategorys(): Collection
    {
        return $this->jobcategorys;
    }

    public function addJobcategory(JobCategory $jobcategory): static
    {
        if (!$this->jobcategorys->contains($jobcategory)) {
            $this->jobcategorys->add($jobcategory);
        }

        return $this;
    }

    public function removeJobcategory(JobCategory $jobcategory): static
    {
        $this->jobcategorys->removeElement($jobcategory);

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, JobApplication>
     */
    public function getJobApplications(): Collection
    {
        return $this->jobApplications;
    }

    public function addJobApplication(JobApplication $jobApplication): static
    {
        if (!$this->jobApplications->contains($jobApplication)) {
            $this->jobApplications->add($jobApplication);
            $jobApplication->setJob($this);
        }

        return $this;
    }

    public function removeJobApplication(JobApplication $jobApplication): static
    {
        if ($this->jobApplications->removeElement($jobApplication)) {
            // set the owning side to null (unless already changed)
            if ($jobApplication->getJob() === $this) {
                $jobApplication->setJob(null);
            }
        }

        return $this;
    }
}
