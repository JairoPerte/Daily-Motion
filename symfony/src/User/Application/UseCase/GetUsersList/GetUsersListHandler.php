<?php

namespace App\User\Application\UseCase\GetUsersList;

use App\User\Application\Service\ThrowExceptionIfUsersLimitMax;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;

class GetUsersListHandler
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ThrowExceptionIfUsersLimitMax $throwExceptionIfUsersLimitMax
    ) {}

    /**
     * @return User[]
     */
    public function __invoke(GetUsersListCommand $command): array
    {
        ($this->throwExceptionIfUsersLimitMax)($command->limit);

        return $this->userRepository->findUsersBySearch(
            $command->search,
            $command->limit,
            $command->page
        );
    }
}
