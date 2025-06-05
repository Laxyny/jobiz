<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobType;
use App\Entity\JobCategory;
use App\Entity\JobApplication;
use App\Enum\JobTypeNameEnum;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // JobTypes
        $jobTypes = [];
        foreach (JobTypeNameEnum::cases() as $enum) {
            $jobType = new JobType();
            $jobType->setName($enum->value);
            $manager->persist($jobType);
            $jobTypes[] = $jobType;
        }

        // JobCategories
        $categories = [];
        for ($i = 0; $i < 8; $i++) {
            $cat = new JobCategory();
            $cat->setName($this->faker->unique()->word());
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // Companies
        $companies = [];
        for ($i = 0; $i < 10; $i++) {
            $company = new Company();
            $company->setName($this->faker->company())
                ->setDescription($this->faker->paragraph(3))
                ->setAddress($this->faker->address())
                ->setCity($this->faker->city())
                ->setCountry($this->faker->countryCode());
            $manager->persist($company);
            $companies[] = $company;
        }

        // Users
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail())
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);
            $users[] = $user;
        }

        // Jobs
        $jobs = [];
        for ($i = 0; $i < 30; $i++) {
            $job = new Job();
            $job->setTitle($this->faker->jobTitle())
                ->setDescription($this->faker->paragraph(4))
                ->setCountry($this->faker->countryCode())
                ->setCity($this->faker->city())
                ->setRemoteAllowed($this->faker->boolean(40))
                ->setSalaryRangeMin($this->faker->numberBetween(20000, 40000))
                ->setSalaryRangeMax($this->faker->numberBetween(40001, 90000))
                ->setJobtype($this->faker->randomElement($jobTypes))
                ->setCompany($this->faker->randomElement($companies));
            // Ajout de catégories (ManyToMany)
            $jobCats = $this->faker->randomElements($categories, $this->faker->numberBetween(1, 3));
            foreach ($jobCats as $cat) {
                $job->addJobcategory($cat);
            }
            $manager->persist($job);
            $jobs[] = $job;
        }

        // JobApplications
        for ($i = 0; $i < 50; $i++) {
            $app = new JobApplication();
            $app->setCoverLetter($this->faker->paragraph(2))
                ->setCreatedAt(
                    \DateTimeImmutable::createFromMutable(
                        $this->faker->dateTimeBetween('-6 months')
                    )
                )
                ->setJob($this->faker->randomElement($jobs))
                ->setUtilisateur($this->faker->randomElement($users));
            $manager->persist($app);
        }

        $manager->flush();
    }
}
