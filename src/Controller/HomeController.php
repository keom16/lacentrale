<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */

    public function homeShow()
    {

        return $this->render('accueil.html.twig');
    }

    /**
     * @Route("/admin-lacentrale", name="admin-lacentrale")
     */

    public function homeadminShow()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //permet d'autoriser l'accès aux administrateurs.
        return $this->render('common/admin.html.twig');
    }

}