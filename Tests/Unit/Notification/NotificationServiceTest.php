<?php

declare(strict_types=1);

namespace App\Tests\Unit\Notification;

use App\Entity\User;
use App\Model\Notification;
use App\Notification\NotificationRuleInterface;
use App\Service\NotificationService;
use PHPUnit\Framework\TestCase;

class NotificationServiceTest extends TestCase
{
    public function testGetNotificationsForUserFiltersRulesCorrectly(): void
    {
        $user = $this->createMock(User::class);
        $notification1 = $this->createMock(Notification::class);
        $notification2 = $this->createMock(Notification::class);

        $ruleMatch = $this->createMock(NotificationRuleInterface::class);
        $ruleMatch->method('supports')->with($user)->willReturn(true);
        $ruleMatch->method('getNotification')->willReturn($notification1);

        $ruleNoMatch = $this->createMock(NotificationRuleInterface::class);
        $ruleNoMatch->method('supports')->with($user)->willReturn(false);
        $ruleNoMatch->expects($this->never())->method('getNotification');

        $ruleMatch2 = $this->createMock(NotificationRuleInterface::class);
        $ruleMatch2->method('supports')->with($user)->willReturn(true);
        $ruleMatch2->method('getNotification')->willReturn($notification2);

        $rules = [$ruleMatch, $ruleNoMatch, $ruleMatch2];
        $service = new NotificationService($rules);

        $results = $service->getNotificationsForUser($user);

        $this->assertCount(2, $results);
        $this->assertSame($notification1, $results[0]);
        $this->assertSame($notification2, $results[1]);
    }

    public function testReturnsEmptyArrayWhenNoRulesSupportUser(): void
    {
        $user = $this->createMock(User::class);
        
        $rule = $this->createMock(NotificationRuleInterface::class);
        $rule->method('supports')->willReturn(false);

        $service = new NotificationService([$rule]);
        
        $this->assertEmpty($service->getNotificationsForUser($user));
    }
}