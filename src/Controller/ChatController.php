<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\ChatDto;
use App\Service\ChatService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    public function __construct(
        protected ChatService $chatService,
        protected UserService $userService
    ) {
    }

    #[Route('/chats/start', name: 'start_chat', methods: ['GET'])]
    public function startChatPage(): Response
    {
        return $this->render('start_chat.html.twig');
    }

    #[Route('/chats/{chatId}/close', name: 'show_close_chat_form', methods: ['GET'])]
    public function showCloseChatForm(int $chatId): Response
    {
        return $this->render('close_chat_form.html.twig', ['chatId' => $chatId]);
    }

    #[Route('/chats', name: 'create_chat', methods: ['POST'])]
    public function createChat(Request $request): RedirectResponse
    {
        $name = $request->request->get('name');
        $user = $this->userService->createUser($name);
        $chat = $this->chatService->createChat();
        return $this->redirectToRoute('message_form', ['chatId' => $chat->getId(), 'userId' => $user->getId()]);
    }

    #[Route('/chats/{id}/close', name: 'close_chat', methods: ['POST'])]
    public function closeChat(int $id): JsonResponse
    {
        $this->chatService->closeChat($id);
        return $this->json(['status' => 'Chat closed']);
    }

    #[Route('/chats/{id}/connect', name: 'connect_operator', methods: ['PATCH'])]
    public function connectOperator(int $id): JsonResponse
    {
        $this->chatService->connectOperator($id);
        return $this->json(['status' => 'Operator connected', 'chatId' => $id]);
    }

    #[Route('/chats/resolve', name: 'resolve_issue', methods: ['PATCH'])]
    public function resolveIssue(
        #[MapRequestPayload] ChatDto $chatDto
    ): JsonResponse {
        $chatDto->validate();
        $resolved = $chatDto->resolved ?? false;
        $this->chatService->resolveIssue($chatDto);
        return $this->json(['status' => 'resolved', 'chatId' => $chatDto->chatId, 'resolved' => $resolved]);
    }
}
