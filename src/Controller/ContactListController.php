<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactListController extends AbstractController
{
    /**
     * @Route("/contact-list", name="contact_list")
     */
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact_list/index.html.twig', [
            'controller_name' => 'ContactListController',
            'rows' => $contactRepository->findAll()
        ]);
    }

    /**
     * @Route("/contact-list/ajax", name="contact_list_get")
     */
    public function ajax_fetch_batch(Request $request, ContactRepository $contactRepository): Response {
        $filters = array();

        if (!empty($request->get('s'))){
            $filters['search'] = $request->get('s');
        }
        if (!empty($request->get('g'))){
            $filters['gender'] = $request->get('g');
        }

        $rows = $contactRepository->findFiltered($filters);

        return $this->render('contact_list/ajax.html.twig', [
            'controller_name' => 'ContactListController',
            'rows' => $rows
        ]);
    }
}
