<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobApplication;
use App\Entity\JobCategory;
use App\Entity\JobType;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jobiz');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Company', 'fas fa-list', Company::class);
        yield MenuItem::linkToCrud('Job Application', 'fas fa-list', JobApplication::class);
        yield MenuItem::linkToCrud('Job Category', 'fas fa-list', JobCategory::class);
        yield MenuItem::linkToCrud('Job', 'fas fa-list', Job::class);
        yield MenuItem::linkToCrud('Job Type', 'fas fa-list', JobType::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
    }
}
