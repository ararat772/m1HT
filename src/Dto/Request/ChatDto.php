<?php

declare(strict_types=1);

namespace App\Dto\Request;

class ChatDto
{
    public ?int $chatId = null;
    public ?bool $resolved = null;

    /**
     * Validate the properties of the DTO.
     *
     * @throws \AssertionError if validation fails.
     */
    public function validate(): void
    {
        assert($this->chatId !== null, new \AssertionError('chatId must not be null'));
        assert($this->resolved !== null, new \AssertionError('resolved status must not be null'));
    }
}
