<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\MessageService;
use App\Dto\Request\MessageSendDto;
use App\Trait\ResponseWithJsonTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    use ResponseWithJsonTrait;

    public function __construct(protected MessageService $messageService)
    {
    }

    #[Route('messages/new/{chatId}/{userId}', name: 'message_form', methods: ['GET'])]
    public function showMessageForm(int $chatId, int $userId): Response
    {
        return $this->render('message_form.html.twig', ['chatId' => $chatId, 'userId' => $userId]);
    }

    #[Route('/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request): RedirectResponse
    {
        $data = $request->request->all();

        $message = $this->messageService->sendMessage($data);
        $chatId  = $message->getChat()->getId();

        return $this->redirectToRoute('show_close_chat_form', ['chatId' => $chatId]);
    }

    #[Route('/messages/{id}/read', methods: ['PATCH'])]
    public function readMessage(int $id): JsonResponse
    {
        $this->messageService->markMessageAsRead($id);

        return $this->response([]);
    }

    #[Route('/messages/reply', methods: ['POST'])]
    public function replyMessage(
        #[MapRequestPayload]
        MessageSendDto $messageDto
    ): JsonResponse {
        $messageDto->validate();
        $message = $this->messageService->replyMessage($messageDto);

        return $this->response(['messageId' => $message->getId()]);
    }
}
