<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;


use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\ORM\EntityManagerInterface;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

         $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 10),
            10
        );


    	#$ingredients = $repository->findAll();#on va accédé au donné du bd ingredient grace au repository avec le  fichier IngredientRepository.php
    	#dd($ingredients);

        return $this->render('pages/ingredient/index.html.twig', [
            'controller_name' => 'IngredientController',
            'ingredients' => $ingredients #
        ]);
    }


     /**
     * This controller show a form which create an ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/creation', 'ingredient.new')]
    public function new(Request $request, EntityManagerInterface $manager): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);#accéde au form qui est ds IngredientType

        #rendre le formulaire et gérer la soumission du formulaire.
        $form->handleRequest($request);
        #si le form est valide et soumise
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            #$ingredient->setUser($this->getUser());

            #dd($ingredient);

            $manager->persist($ingredient);#on l'ajoute à la BD 
            $manager->flush();

       
            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès !'
            );


            return $this->redirectToRoute('ingredient.index');
        }

        #la render()méthode appelle $form->createView()pour transformer le formulaire en une instance de vue de formulaire (creer le form pr la vue)
        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * This controller allow us to edit an ingredient
     *
     * @param Ingredient $ingredient
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(
        Ingredient $ingredient, #recupérer id a modifier sur la bd
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec succès !'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


     /**
     * This controller allows us to delete an ingredient
     *
     * @param EntityManagerInterface $manager
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        Ingredient $ingredient
    ): Response {
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !'
        );

        return $this->redirectToRoute('ingredient.index');
    }



}
