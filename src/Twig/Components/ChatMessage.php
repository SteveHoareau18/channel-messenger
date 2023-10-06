<?php

namespace App\Twig\Components;

use App\Entity\Message;
use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class ChatMessage
{
    public Message $message;
    public User $me;
}
