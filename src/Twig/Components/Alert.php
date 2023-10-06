<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class Alert
{
    public string $type;
    public string $message;
    public string $title;
}
