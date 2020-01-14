<?php


namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/admin-lacentrale/article/insert", name="admin_article_insert_form")
     */

    public function insertArticleForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouvel Article,
        // en créant une nouvelle instance de l'entité Article
        $article = new Article();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour l'article: ArticleType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Article vide
        $articleForm = $this->createForm(ArticleType::class, $article);
        // à partir de mon gabarit, je crée la vue de mon formulaire
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $articleForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($articleForm->isValid()) {
                // J'enregistre en BDD ma variable $article
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('admin_articles_list'); /**
 **********************************************************/
            }

        }

        $articleFormView = $articleForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('admin/article/ajout_article.html.twig', [
            'articleFormView' => $articleFormView
        ]);
    }

    /**
     * @Route("/articleslist", name="articles_list");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Article
    public function ArticlesList(ArticleRepository $articleRepository)
    {
        //J'utilise le repository d'article pour pouvoir selectionner tous les élèments de ma table article
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $articles = $articleRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('article/articles.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/article/{id}", name="article");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Article
    public function ArticleList(ArticleRepository $articleRepository, $id)
    {
        $article = $articleRepository->find($id);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('article/article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin-lacentrale/articleslist", name="admin_articles_list");
     */

    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Article
    public function ArticlesAdminList(ArticleRepository $articleRepository)
    {
        //J'utilise le repository d'article pour pouvoir selectionner tous les élèments de ma table article
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $articles = $articleRepository->findAll();

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('admin/article/articles.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/admin-lacentrale/article/{id}", name="admin_article");
     */

    //méthode qui permet de faire "un select" en BDD d'un id dans ma table Article
    public function ArticleAdminList(ArticleRepository $articleRepository, $id)
    {
        $article = $articleRepository->find($id);

        //méthode render sui permet d'afficher mon fichier html.twig, et le résultat de ma requête SQL
        return $this->render('admin/article/article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin-lacentrale/article/update/{id}", name="admin_article_update")
     */
    
    public function updateArticleForm(ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $article = $articleRepository->find($id);
        $articleForm = $this->createForm(articleType::class, $article);
        if ($request->isMethod('Post'))
        {
            $articleForm->handleRequest($request);
            if ($articleForm->isValid()) {
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('admin_articles_list');
            }

        }

        $articleFormView = $articleForm->createView();

        return $this->render('admin/article/article_update.html.twig', [
            'articleFormView' => $articleFormView
        ]);
    }

    /**
     * @Route("/admin-lacentrale/article/delete/{id}", name="admin_article_delete")
     */
    public function deleteArticle(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement article en BDD grâce au repository d'article
        $article = $articleRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de l'article dans l'unité de travail
        $entityManager->remove($article);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('admin/article/articledelete.html.twig', [
            'article'=> $article
        ]);
    }

}