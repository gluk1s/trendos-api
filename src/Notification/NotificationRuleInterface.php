<?php

declare(strict_types=1);

namespace App\Notification;

use App\Entity\User;
use App\Model\Notification;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface NotificationRuleInterface
{
    public function supports(User $user): bool;

    public function getNotification(): Notification;
}