<?php

declare(strict_types=1);

namespace App\Notification;

use App\Entity\User;
use App\Model\Notification;
use App\Factory\NotificationFactory;
use DateTimeImmutable;

class AndroidDeviceMissingRule implements NotificationRuleInterface
{
    public function __construct(
        private NotificationFactory $factory
    ) {}

    public function supports(User $user): bool
    {
        if ($user->isPremium()) {
            return false;
        }

        if ($user->getCountryCode() !== 'ES') {
            return false;
        }

        if ($user->getLastActiveAt() > new DateTimeImmutable('-7 days')) {
            return false;
        }

        foreach ($user->getDevices() as $device) {
            if ($device->getPlatform() === 'android') {
                return false;
            }
        }
        
        return true;
    }

    public function getNotification(): Notification
    {
        return $this->factory->create(
            'Configurar dispositivo Android',
            'Phasellus rhoncus ante dolor, at semper metus aliquam quis. Praesent finibus pharetra libero, ut feugiat mauris dapibus blandit. Donec sit.',
            'https://trendos.com/'
        );
    }
}