<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactUsController extends AbstractController
{
    public $request;
    public $entityManager;
    public $mailer;

    /**
     * @Route("/contact-us", name="contact_us")
     */
    public function index(): Response
    {
        return $this->render('contact_us/index.html.twig');
    }

    /**
    * @Route("/contact/create", name="contact_create")
    */
    public function contact_new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): JsonResponse
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        $data = array();

        if ($request->getContentType() == 'json'){
            $data = json_decode($request->getContent(), true);
        }

        if (!empty($data)){
            $contact = $this->create($data);
            $this->send_mail($data);

            $responseJson = array('success'=>true, 'id' => $contact->getId());
            return JsonResponse::create($responseJson);
        }

        $responseJson = array('success'=>false, 'msg' => '');      
        return JsonResponse::create($responseJson);
    }

    private function send_mail($data){
        $email = (new Email());
        $email->from('neiljsteyn@gmail.com');
        $email->to($data['email']);
        $email->subject('New Contact Request');
        $email->html('Hi Admin</br>A new contact has been recieved.');
        $this->mailer->send($email);
    }

    private function create($data){
        $contact = new Contact();
        $contact->setName($data['name']);
        $contact->setEmail($data['email']);
        $contact->setGender($data['gender']);
        $contact->setContent($data['content']);

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return $contact;
    }
}
