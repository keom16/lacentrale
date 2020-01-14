<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer")
 */
class OfferController extends AbstractController
{

    /**
     * @Route("/admin-lacentrale/insert", name="admin_offer_insert_form")
     */

    public function insertOfferForm(Request $request, EntityManagerInterface $entityManager)
    {
        $offer = new offer();
        
        $offerForm = $this->createForm(offerType::class, $offer);
        if ($request->isMethod('Post')) {
            $offerForm->handleRequest($request);
            if ($offerForm->isValid()) {
                $entityManager->persist($offer);
                $entityManager->flush();

                return $this->redirectToRoute('admin_offer_list');
            }
        }

        $offerFormView = $offerForm->createView();
        
        return $this->render('admin/offer/ajout_offer.html.twig', [
            'offerFormView' => $offerFormView
        ]);
    }

    /**
     * @Route("/", name="offer_index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin-lacentrale/", name="admin_offer_list")
     */
    public function offersAdminList(OfferRepository $offerRepository)
    {
        return $this->render('admin/offer/offers.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_show", methods={"GET"})
     */
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }



    /**
     * @Route("/admin-lacentrale/{id}", name="admin_offer", methods={"GET"})
     */
    public function offerAdminList(Offer $offer): Response
    {
        return $this->render('admin/offer/offer.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/admin-lacentrale/offer/update/{id}", name="admin_offer_update")
     */

    public function updateOfferForm(offerRepository $offerRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $offer = $offerRepository->find($id);
        $offerForm = $this->createForm(offerType::class, $offer);
        if ($request->isMethod('Post'))
        {
            $offerForm->handleRequest($request);
            if ($offerForm->isValid()) {
                $entityManager->persist($offer);
                $entityManager->flush();

                return $this->redirectToRoute('admin_offer_list');
            }

        }

        $offerFormView = $offerForm->createView();

        return $this->render('admin/offer/offer_update.html.twig', [
            'offerFormView' => $offerFormView
        ]);
    }

    /**
     * @Route("/admin-lacentrale/offer/delete/{id}", name="admin_offer_delete")
     */

    public function deleteOffer(offerRepository $offerRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement offer en BDD grâce au repository d'offer
        $offer = $offerRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de l'offer dans l'unité de travail
        $entityManager->remove($offer);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('admin/offer/offerdelete.html.twig', [
            'offer'=> $offer
        ]);
    }
}
