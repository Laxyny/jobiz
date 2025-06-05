<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
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
    public function index(JobRepository $jobRepository): Response
    {
        $jobs = $jobRepository->findAll();

        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
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
}
