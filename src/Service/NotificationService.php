<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Notification\NotificationRuleInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NotificationService
{


    public function __construct(
        #[AutowireIterator(NotificationRuleInterface::class)]
        private iterable $rules
    ) {}

    public function getNotificationsForUser(User $user): array
    {
        $notifications = [];

        foreach ($this->rules as $rule) {
            if ($rule->supports($user)) {
                $notifications[] = $rule->getNotification();
            }
        }

        return $notifications;
    }
}
