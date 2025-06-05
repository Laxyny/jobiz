<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\JobApplication;
use App\Form\PostulateTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostulateController extends AbstractController
{
    #[Route('/job/{id}/postulate', name: 'app_job_postulate', methods: ['POST'])]
    public function postulate(
        Job $job,
        Request $request,
        Security $security,
        EntityManagerInterface $em
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour postuler');
            return $this->redirectToRoute('app_login');
        }

        $existingApplication = $em->getRepository(JobApplication::class)
            ->findOneBy(['Job' => $job, 'utilisateur' => $user]);

        if ($existingApplication) {
            $this->addFlash('info', 'Vous avez déjà postulé à cette offre');
            return $this->redirectToRoute('app_job_show', ['id' => $job->getId()]);
        }

        $application = new JobApplication();
        $application->setJob($job);
        $application->setUtilisateur($user);
        $application->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(PostulateTypeForm::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($application);
            $em->flush();

            $this->addFlash('success', 'Votre candidature a été envoyée avec succès !');
        } else {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre candidature.');
        }

        return $this->redirectToRoute('app_job_show', ['id' => $job->getId()]);
    }
}
