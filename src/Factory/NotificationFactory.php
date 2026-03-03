<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\Notification;

class NotificationFactory
{
    public function create(string $title, string $description, string $cta): Notification
    {
        return (new Notification())
            ->setTitle($title)
            ->setDescription($description)
            ->setCta($cta);
    }
}
