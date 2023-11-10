<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('message/index.html.twig', [
            'users'             => $userRepository->findAll()
        ]);
    }

    #[Route('/{id}/conv', name: 'app_message_conv', methods: ['GET', 'POST'])]
    public function conv($id, Request $request, UserRepository $userRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $message = new Message();
        $user = $security->getUser();
        $dest = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            return $this->redirectToRoute('app_home');
        }

        $message->setUser1($user);
        $message->setUser2($dest);

        $message->setCreatedDate(new DateTime());

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message_conv', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/conv.html.twig', [
            'send_messages'     => $messageRepository->findBy([
                'user1' => $user, 
                'user2' => $dest
            ]),
            'received_messages' => $messageRepository->findBy([
                'user1' => $dest,
                'user2' => $user
            ]),
            'message'   => $message,
            'form'      => $form
        ]);
    }
}
