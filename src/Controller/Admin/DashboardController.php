<?php

namespace App\Controller\Admin;

use App\Entity\Workflow;
use App\Entity\Workflow\Place;
use App\Entity\WorkflowPlace;
use App\Entity\WorkflowTransition;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', []);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Workflow', 'fas fa-list', Workflow::class);
        yield MenuItem::linkToCrud('WorkflowState', 'fas fa-list', WorkflowPlace::class);
        yield MenuItem::linkToCrud('WorkflowTransition', 'fas fa-list', WorkflowTransition::class);

        $workflows = $this->entityManager
            ->getRepository(Workflow::class)
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult();

        foreach ($workflows as $target) {
            yield MenuItem::linkToRoute(
                'Workflow ' . $target->getId(),
                'fa fa-diagram-project',
                'admin_workflow_visualization',
                ['workflow' => $target->getId()]
            );
        }
    }
}
