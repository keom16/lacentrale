<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */

    public function homeShow(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('accueil.html.twig', [
            'articles'=> $articles
        ]);
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