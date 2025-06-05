<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Form\JobSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\JobApplication;
use App\Form\PostulateTypeForm;
use App\Form\JobForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

final class JobController extends AbstractController
{
    #[Route('/job/list', name: 'app_job_list')]
    public function index(JobRepository $jobRepository, EntityManagerInterface $em, Request $request): Response
    {
        $jobs = $jobRepository->findAll();

        $form = $this->createForm(JobSearchType::class, null, [
            'method' => 'GET',
        ]);

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/job/{id}', name: 'app_job_show')]
    public function show(
        Job $job,
        Security $security,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $user = $security->getUser();
        $hasApplied = false;
        $applicationForm = null;

        if ($user) {
            $existingApplication = $em->getRepository(JobApplication::class)
                ->findOneBy(['Job' => $job, 'utilisateur' => $user]);

            if ($existingApplication) {
                $hasApplied = true;
            } else {
                $application = new JobApplication();
                $application->setJob($job);
                $application->setUtilisateur($user);
                $application->setCreatedAt(new \DateTimeImmutable());

                $applicationForm = $this->createForm(PostulateTypeForm::class, $application, [
                    'action' => $this->generateUrl('app_job_postulate', ['id' => $job->getId()]),
                    'method' => 'POST',
                ]);
            }
        }

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasApplied' => $hasApplied,
            'applicationForm' => $applicationForm ? $applicationForm->createView() : null,
            'isLoggedIn' => ($user !== null)
        ]);
    }

    #[Route('/ajouter-offre', name: 'app_job_add')]
    public function addJob(Request $request, EntityManagerInterface $em): Response
    {
        $job = new Job();
        $form = $this->createForm(JobForm::class, $job);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'Offre ajoutée avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('page/add_job.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/jobs', name: 'app_job_list')]
    public function list(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $repository = $em->getRepository(Job::class);

        $form = $this->createForm(JobSearchType::class, null, [
            'method' => 'GET',
        ]);

        if ($search) {
            // Recherche avec critère
            $jobs = $repository->createQueryBuilder('j')
                ->where('j.title LIKE :search')
                ->orWhere('j.description LIKE :search')
                ->orWhere('j.city LIKE :search')
                ->orWhere('j.country LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('j.id', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $jobs = $repository->findAll();
        }

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'search' => $search,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/job/filtrer', name: 'app_job_filter')]
    public function filter(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(JobSearchType::class, null, [
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        $qb = $em->getRepository(Job::class)->createQueryBuilder('j')
            ->leftJoin('j.jobcategorys', 'c')
            ->addSelect('c');

        $data = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($data['category'])) {
                $qb->andWhere(':cat MEMBER OF j.jobcategorys')
                    ->setParameter('cat', $data['category']);
            }
            if (!empty($data['salary_min'])) {
                $qb->andWhere('j.salary_range_min >= :salary_min')
                    ->setParameter('salary_min', $data['salary_min']);
            }
            if (!empty($data['salary_max'])) {
                $qb->andWhere('j.salary_range_max <= :salary_max')
                    ->setParameter('salary_max', $data['salary_max']);
            }
        }

        $jobs = $qb->orderBy('j.id', 'DESC')->getQuery()->getResult();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
            'form' => $form->createView(),
        ]);
    }
}
