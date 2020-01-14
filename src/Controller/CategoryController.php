<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/admin-lacentrale/new", name="admin_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categorys_list');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'categoryFormView' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin-lacentrale/categorieslist", name="admin_categories_list");
     */

    public function categoriesAdminList(categoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/categories.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/admin-lacentrale/category/{id}", name="admin_category");
     */

    public function categoryAdminList(categoryRepository $categoryRepository, $id)
    {
        $category = $categoryRepository->find($id);

        return $this->render('admin/category/category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/admin-lacentrale/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    /*  public function edit(Request $request, Category $category): Response
     {
         $form = $this->createForm(CategoryType::class, $category);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $this->getDoctrine()->getManager()->flush();

             return $this->redirectToRoute('category_index');
         }

         return $this->render('category/edit.html.twig', [
             'category' => $category,
             'form' => $form->createView(),
         ]);
     }*/

    /**
     * @Route("/admin-lacentrale/category/update/{id}", name="admin_category_update")
     */

    public function updateCategoryForm(categoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager, $id)
    {
        $category = $categoryRepository->find($id);
        $categoryForm = $this->createForm(categoryType::class, $category);
        if ($request->isMethod('Post'))
        {
            $categoryForm->handleRequest($request);
            if ($categoryForm->isValid()) {
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirectToRoute('admin_categorys_list');
            }

        }

        $categoryFormView = $categoryForm->createView();

        return $this->render('admin/category/category_update.html.twig', [
            'categoryFormView' => $categoryFormView
        ]);
    }


    /**
     * @Route("/admin-lacentrale/{id}", name="admin_category_delete", methods={"DELETE"})
     */

    public function deleteCategory(categoryRepository $categoryRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement category en BDD grâce au repository de category
        $category = $categoryRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de l'category dans l'unité de travail
        $entityManager->remove($category);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('admin/category/categorydelete.html.twig', [
            'category'=> $category
        ]);
    }
}
