<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index(ProductRepository $repo): Response
    {
        $bikes = $repo->findAll();

        return $this->render('product/index.html.twig', [
            'bikes' => $bikes,
        ]);
    }
    /**
     * @Route("/products/{id}")
     */
    public function details($id, ProductRepository $repo,Request $request,SessionInterface $session): Response
    {
         $bike = $repo->find($id);

        if ($bike === null) {
            throw $this->createNotFoundException('The product does not exist');
        }

        // add to basket handling
        $basket = $session->get('basket', []);

        if ($request->isMethod('POST')) {
            $basket[$bike->getId()] = $bike;
            $session->set('basket', $basket);
        }

        $isInBasket = array_key_exists($bike->getId(), $basket);
        
        return $this->render('product/details.html.twig', [
            'bike' => $bike,
            'inBasket' => !$isInBasket
        ]);
    }
}
