<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\SubmitType;
use Symfony\Component\Form\Extension\Core\TextType;
use Symfony\Component\Form\Extension\Core\TextareaType;

use App\Entity\Order; 
class CheckoutController extends AbstractController
{
    
    /**
     * @Route("/checkout")
     */
    public function checkout( SessionInterface $session): Response
    { 
        // add to basket handling
        $basket = $session->get('basket', []);
        $total = array_sum(array_map(function($product) {return $product->getPrice();}, $basket));  

        $order = new Order;

        $form = $this->createFormBuilder($order)
        ->add('name',TextType::class)
        ->add('email',TextType::class) 
        ->add('address',TextareaType::class)
        ->add('save',SubmitType::class,['label'=>'Confirm order'])
        ->getForm();  
        
        return $this->render('checkout.html.twig', [ 
            'form'=>$form->createView(), 
            'total'=>$total,
        ]);
    }
}
