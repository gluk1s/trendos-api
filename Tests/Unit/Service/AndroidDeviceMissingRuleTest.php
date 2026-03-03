<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Device;
use App\Entity\User;
use App\Factory\NotificationFactory;
use App\Model\Notification;
use App\Notification\AndroidDeviceMissingRule;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AndroidDeviceMissingRuleTest extends TestCase
{
    private $factory;
    private $rule;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(NotificationFactory::class);
        $this->rule = new AndroidDeviceMissingRule($this->factory);
    }

    #[DataProvider('userProvider')]
    public function testSupports(
        bool $isPremium,
        string $country,
        DateTimeImmutable $lastActive,
        array $platforms,
        bool $expected
    ): void {
        $user = $this->createMock(User::class);
        $user->method('isPremium')->willReturn($isPremium);
        $user->method('getCountryCode')->willReturn($country);
        $user->method('getLastActiveAt')->willReturn($lastActive);

        $devices = new ArrayCollection();
        foreach ($platforms as $platform) {
            $device = $this->createMock(Device::class);
            $device->method('getPlatform')->willReturn($platform);
            $devices->add($device);
        }
        $user->method('getDevices')->willReturn($devices);

        $this->assertSame($expected, $this->rule->supports($user));
    }

    public static function userProvider(): array
    {
        return [
            'valid_user_should_pass' => [false, 'ES', new DateTimeImmutable('-10 days'), ['windows'], true],
            'premium_user_should_fail' => [true, 'ES', new DateTimeImmutable('-10 days'), ['windows'], false],
            'non_spanish_user_should_fail' => [false, 'FR', new DateTimeImmutable('-10 days'), ['windows'], false],
            'active_user_should_fail' => [false, 'ES', new DateTimeImmutable('-2 days'), ['windows'], false],
            'user_with_android_should_fail' => [false, 'ES', new DateTimeImmutable('-10 days'), ['android'], false],
        ];
    }

    public function testGetNotificationCallsFactory(): void
    {
        $expectedNotification = $this->createMock(Notification::class);

        $this->factory->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo('Configurar dispositivo Android'),
                $this->callback(fn($subject) => is_string($subject)), 
                $this->equalTo('https://trendos.com/')
            )
            ->willReturn($expectedNotification);

        $result = $this->rule->getNotification();
        $this->assertSame($expectedNotification, $result);
    }
}
