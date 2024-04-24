<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Chat;
use App\Dto\Request\ChatDto;
use App\Exception\AppException;
use App\Repository\ChatRepository;

class ChatService extends AbstractService
{
    public function __construct(protected ChatRepository $chatRepository)
    {
    }

    public function createChat(): Chat
    {
        $chat = new Chat();
        $chat->setStatus('open');
        $chat->setCreatedAt(new \DateTime());
        $chat->setUpdatedAt(new \DateTime());

        $this->saveChat($chat);
        return $chat;
    }

    public function closeChat(int $id): void
    {
        $chat = $this->chatRepository->find($id);

        if (!$chat) {
            throw new AppException('Chat not found');
        }

        $chat->setStatus('closed');
        $chat->setUpdatedAt(new \DateTime());

        $this->saveChat($chat);
    }

    public function connectOperator(int $chatId): void
    {
        $chat = $this->chatRepository->find($chatId);
        if (!$chat) {
            throw new AppException('Chat not found');
        }

        $chat->setStatus('operator connected');
        $chat->setUpdatedAt(new \DateTime());

        $this->saveChat($chat);
    }

    public function resolveIssue(ChatDto $chatDto): void
    {
        $chat = $this->chatRepository->find($chatDto->chatId);
        if (!$chat) {
            throw new AppException('Chat not found');
        }

        $chat->setIsResolved($chatDto->resolved);
        $chat->setUpdatedAt(new \DateTime());

        $this->saveChat($chat);
    }

    public function saveChat(Chat $chat): void
    {
        $this->em->persist($chat);
        $this->em->flush();
    }
}
