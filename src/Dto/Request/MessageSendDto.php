<?php

declare(strict_types=1);

namespace App\Dto\Request;

class MessageSendDto
{
    public ?int $chatId = null;
    public ?int $userId = null;
    public ?string $text = null;

    /**
     * Validate the properties of the DTO.
     *
     * @throws \AssertionError if validation fails.
     */
    public function validate(): void
    {
        assert($this->chatId !== null, new \AssertionError('chatId must not be null'));
        assert($this->userId !== null, new \AssertionError('userId must not be null'));
        assert($this->text !== null && trim($this->text) !== '', new \AssertionError('Text must not be empty'));
    }
}
