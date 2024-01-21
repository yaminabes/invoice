<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Form\InvoiceDateRangeType;
use App\Repository\ActivityRepository;
use App\Service\PdfInvoiceGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntrepriseRepository;

/**
 * @Route("/activity")
 */
class ActivityController extends AbstractController
{
    /**
     * @Route("/", name="app_activity_index", methods={"GET"})
     */
    public function index(ActivityRepository $activityRepository): Response
    {
        $currentUser = $this->getUser();
        return $this->render('activity/index.html.twig', [
            'activities' => $activityRepository->findActivitiesByResponsable($currentUser),
        ]);
    }

    /**
     * @Route("/generate-invoice", name="app_show_activities", methods={"GET","POST"})
     */
    public function generateInvoice(Request $request, ActivityRepository $activityRepository, PdfInvoiceGenerator $pdfService, EntrepriseRepository $entrepriseRepository): Response
    {
        // Create the form
        $form = $this->createForm(InvoiceDateRangeType::class);

        // Handle form submissions
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('start_date')->getData();
            $endDate = $form->get('end_date')->getData();
            $totalCost = 0;

            //responsable
            $currentUser = $this->getUser();
            // all activities between two dates
            $activities = $activityRepository->findActivitiesBetweenDates($startDate, $endDate, $currentUser);
            $firstClientEntreprise = $entrepriseRepository->findOneBy(['isClient' => true], ['id' => 'ASC']);
            $firstCurrentEntreprise = $entrepriseRepository->findOneBy(['isCurrent' => true], ['id' => 'ASC']);

            foreach ($activities as $activity) {
                if ($activity->isStatus()) {
                    $totalCost += $activity->calculateTotalCost();
                }
            }
            

            $pdfService->generateInvoice($startDate, $endDate, $firstClientEntreprise, $firstCurrentEntreprise,$activities);
            return $this->render('activity/show_activities.html.twig', [
                'form' => $form->createView(),
                'activities' => $activities,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalCost' => $totalCost,
                'client' => $firstClientEntreprise,
                'current_entreprise' => $firstCurrentEntreprise,
            ]);
        }

        // Render the form template if it's not submitted or invalid
        return $this->render('activity/show_activities_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="app_activity_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ActivityRepository $activityRepository): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_activity_show", methods={"GET"})
     */
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_activity_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="app_activity_delete", methods={"POST"})
     */
    public function delete(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activity->getId(), $request->request->get('_token'))) {
            $activityRepository->remove($activity, true);
        }

        return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
    }



}