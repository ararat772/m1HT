<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Message;
use App\Exception\AppException;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Dto\Request\MessageSendDto;

class MessageService extends AbstractService
{
    public function __construct(
        protected ChatRepository $chatRepository,
        protected UserRepository $userRepository,
        protected MessageRepository $messageRepository
    ) {
    }

    public function sendMessage(array $data): Message
    {
        $chat = $this->chatRepository->find($data['chatId']);
        if (!$chat || $chat->getStatus() === 'closed') {
            throw new AppException('Chat not found or is closed');
        }

        $user = $this->userRepository->find($data['userId']);
        if (!$user) {
            throw new AppException('User not found');
        }

        $message = new Message();
        $message->setChat($chat);
        $message->setUser($user);
        $message->setText($data['text']);
        $message->setCreatedAt(new \DateTime());
        $message->setRead(false);

        $this->saveMessage($message);
        return $message;
    }

    public function markMessageAsRead(int $messageId): void
    {
        $message = $this->messageRepository->find($messageId);
        if (!$message) {
            throw new AppException('Message not found');
        }

        $message->setRead(true);
        $message->setCreatedAt(new \DateTime());

        $this->saveMessage($message);
    }

    public function replyMessage(MessageSendDto $messageDto): Message
    {
        $chat = $this->chatRepository->find($messageDto->chatId);
        if (!$chat) {
            throw new AppException('Chat not found');
        }

        $user = $this->userRepository->find($messageDto->userId);
        if (!$user) {
            throw new AppException('User not found');
        }

        $message = new Message();
        $message->setChat($chat);
        $message->setUser($user);
        $message->setText($messageDto->text);
        $message->setCreatedAt(new \DateTime());
        $message->setRead(false);

        $this->saveMessage($message);

        return $message;
    }

    public function saveMessage(Message $message): void
    {
        $this->em->persist($message);
        $this->em->flush();
    }
}
