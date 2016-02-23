<?php

namespace AppBundle\Controller;

use AppBundle\Entity\JobOffer;
use AppBundle\Form\JobApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JobOfferController extends Controller
{
    /**
     * @Route("/job/{id}/apply", name="app_apply_to_offer", requirements={ "id"="\d+" })
     * @Method("GET|POST")
     */
    public function applyForJobOfferAction(Request $request, JobOffer $job)
    {
        if (!$job->isActive()) {
            throw $this->createNotFoundException(sprintf('Job offer #%u is not active.', $job->getId()));
        }

        $form = $this->createForm(JobApplicationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($form->getData());
            die;
        }

        return $this->render('jobs/apply.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    public function companiesAction()
    {
        $companies = $this
            ->getDoctrine()
            ->getRepository('AppBundle:JobOffer')
            ->findActiveCompanies();

        return $this->render('jobs/companies.html.twig', ['companies' => $companies]);
    }

    /**
     * @Route("/", name="app_homepage")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $pager = $this
            ->getDoctrine()
            ->getRepository('AppBundle:JobOffer')
            ->findMostRecentOffers(null, $request->query->getInt('page', 1));

        return $this->render('jobs/index.html.twig', [
            'pager' => $pager,
            'jobs' => $pager->getResults(),
        ]);
    }

    /**
     * @Route("/job/{id}", name="app_view_job", requirements={
     *   "id"="\d+"
     * })
     * @Method("GET")
     */
    public function viewAction(JobOffer $job)
    {
        if (!$job->isActive()) {
            throw $this->createNotFoundException(sprintf('Job offer #%u is not active.', $job->getId()));
        }

        return $this->render('jobs/job.html.twig', ['job' => $job]);
    }

    /**
     * @Route("/search", name="app_search_jobs")
     * @Route("/search/{keywords}", name="app_specific_search_jobs")
     * @Method("GET|POST")
     */
    public function searchAction(Request $request)
    {
        $pager = $this
            ->getDoctrine()
            ->getRepository('AppBundle:JobOffer')
            ->findMostRecentOffers($request->get('keywords'), $request->query->getInt('page', 1));

        return $this->render('jobs/index.html.twig', [
            'pager' => $pager,
            'jobs' => $pager->getResults(),
        ]);
    }
}
