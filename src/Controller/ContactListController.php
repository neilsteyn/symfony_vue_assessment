<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactListController extends AbstractController
{
    /**
     * @Route("/contact-list", name="contact_list")
     */
    public function index(): Response
    {
        return $this->render('contact_list/index.html.twig', [
            'controller_name' => 'ContactListController',
        ]);
    }
}
