<?php


namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/admin-lacentrale/article/insert", name="admin_article_insert_form")
     */

    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form['images']->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $article->setImages($newFilename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_articles_list'));
        }

        return $this->render('admin/article/ajout_article.html.twig', [
            'articleFormView' => $form->createView(),
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

    public function edit(articleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $article = $articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($request->isMethod('Post'))
        {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $brochureFile */
                $brochureFile = $form['images']->getData();

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('uploads_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $article->setImages($newFilename);
                }

                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('admin_articles_list'));
            }

        }

        $articleFormView = $form->createView();

        return $this->render('admin/article/article_update.html.twig', [
            'articleFormView' => $articleFormView,
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