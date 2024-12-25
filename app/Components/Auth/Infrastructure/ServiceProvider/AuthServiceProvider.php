<?php

namespace App\Components\Auth\Infrastructure\ServiceProvider;

use App\Components\Auth\Application\Factory\UserFactoryInterface;
use App\Components\Auth\Application\Factory\UserVerificationFactoryInterface;
use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Query\NotificationQueryInterface;
use App\Components\Auth\Application\Query\UserOldPasswordQueryInterface;
use App\Components\Auth\Application\Query\UserQueryInterface;
use App\Components\Auth\Application\Query\UserVerificationQueryInterface;
use App\Components\Auth\Application\Repository\NotificationRepositoryInterface;
use App\Components\Auth\Application\Repository\UserOldPasswordRepositoryInterface;
use App\Components\Auth\Application\Repository\UserRepositoryInterface;
use App\Components\Auth\Application\Repository\UserVerificationRepositoryInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Infrastructure\Factory\UserFactory;
use App\Components\Auth\Infrastructure\Factory\UserVerificationFactory;
use App\Components\Auth\Infrastructure\Mapper\UserDtoMapper;
use App\Components\Auth\Infrastructure\Mapper\UserViewModelMapper;
use App\Components\Auth\Infrastructure\Query\NotificationQuery;
use App\Components\Auth\Infrastructure\Query\UserOldPasswordQuery;
use App\Components\Auth\Infrastructure\Query\UserQuery;
use App\Components\Auth\Infrastructure\Query\UserVerificationQuery;
use App\Components\Auth\Infrastructure\Repository\NotificationRepository;
use App\Components\Auth\Infrastructure\Repository\UserOldPasswordRepository;
use App\Components\Auth\Infrastructure\Repository\UserRepository;
use App\Components\Auth\Infrastructure\Repository\UserVerificationRepository;
use App\Components\Auth\Infrastructure\Service\UserService;
use App\Components\Auth\Infrastructure\Service\UserVerificationService;
use App\Libraries\Base\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected function regularBindings(): array
    {
        return [
            UserRepositoryInterface::class => UserRepository::class,
            UserQueryInterface::class => UserQuery::class,
            UserServiceInterface::class => UserService::class,
            UserFactoryInterface::class => UserFactory::class,
            UserDtoMapperInterface::class => UserDtoMapper::class,
            UserViewModelMapperInterface::class => UserViewModelMapper::class,
            UserVerificationFactoryInterface::class => UserVerificationFactory::class,
            UserVerificationQueryInterface::class => UserVerificationQuery::class,
            UserVerificationRepositoryInterface::class => UserVerificationRepository::class,
            UserVerificationServiceInterface::class => UserVerificationService::class,

            NotificationRepositoryInterface::class => NotificationRepository::class,
            NotificationQueryInterface::class => NotificationQuery::class,

            UserOldPasswordRepositoryInterface::class => UserOldPasswordRepository::class,
            UserOldPasswordQueryInterface::class => UserOldPasswordQuery::class,
        ];
    }
}
