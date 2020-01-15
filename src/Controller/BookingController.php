<?php


namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\ArticleRepository;
use App\Repository\BookingRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{

    /**
     * @Route("/new", name="booking_new")
     */
    public function new(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking_send');
        }

        return $this->render('public/reservation.html.twig', [
            'booking' => $booking,
            'bookingFormView' => $form->createView(),
        ]);
    }

    /**
    * @Route("/", name="booking_send")
    */

    public function index(BookingRepository $bookingRepository)
    {
        return $this->render('public/reservation_send.html.twig', [
            'categories' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin-lacentrale", name="admin_bookings")
     */

    public function adminIndex(BookingRepository $bookingRepository)
    {
        return $this->render('admin/booking/reservations.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin-lacentrale/{id}", name="admin_booking")
     */

    public function show(Booking $booking)
    {
        return $this->render('admin/booking/reservation.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/admin-lacentrale/delete/{id}", name="admin_booking_delete")
     */
    public function deleteBooking(BookingRepository $bookingRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement booking en BDD grâce au repository de booking
        $booking = $bookingRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de booking dans l'unité de travail
        $entityManager->remove($booking);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('admin/booking/bookingdelete.html.twig', [
            'booking'=> $booking
        ]);
    }
}