<?php

namespace App\Components\Notification\Infrastructure\ServiceProvider;

use App\Components\Notification\Application\Query\NotificationQueryInterface;
use App\Components\Notification\Application\Query\UserDeviceTokenQueryInterface;
use App\Components\Notification\Application\Repository\NotificationRepositoryInterface;
use App\Components\Notification\Application\Repository\UserDeviceTokenRepositoryInterface;
use App\Components\Notification\Application\Service\EmailNotificationServiceInterface;
use App\Components\Notification\Application\Service\FcmServiceInterface;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Infrastructure\Query\NotificationQuery;
use App\Components\Notification\Infrastructure\Query\UserDeviceTokenQuery;
use App\Components\Notification\Infrastructure\Repository\NotificationRepository;
use App\Components\Notification\Infrastructure\Repository\UserDeviceTokenRepository;
use App\Components\Notification\Infrastructure\Service\EmailNotificationService;
use App\Components\Notification\Infrastructure\Service\FcmService;
use App\Components\Notification\Infrastructure\Service\NotificationService;
use App\Libraries\Base\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    protected function regularBindings(): array
    {
        return [
            NotificationServiceInterface::class => NotificationService::class,
            FcmServiceInterface::class => FcmService::class,
            EmailNotificationServiceInterface::class => EmailNotificationService::class,
            
            NotificationRepositoryInterface::class => NotificationRepository::class,
            UserDeviceTokenRepositoryInterface::class => UserDeviceTokenRepository::class,
            
            NotificationQueryInterface::class => NotificationQuery::class,
            UserDeviceTokenQueryInterface::class => UserDeviceTokenQuery::class,
        ];
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../Resource/routes.php');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Components\Notification\Infrastructure\Console\Commands\SendTargetRemindersCommand::class,
                \App\Components\Notification\Infrastructure\Console\Commands\ProcessScheduledBroadcastsCommand::class,
                \App\Components\Notification\Infrastructure\Console\Commands\CleanOldNotificationsCommand::class,
            ]);
        }
    }
}
