<?php

namespace Qalis\Shared\Domain\Users\FindAuthenticatedUser;

use Qalis\Shared\Domain\Users\UserReadRepository;
use Qalis\Shared\Domain\Users\UserResponse;

final class AuthenticatedUserFinder {

    private UserReadRepository $userReadRepository;

    public function __construct(UserReadRepository $userReadRepository)
    {
        $this->userReadRepository = $userReadRepository;
    }

    public function __invoke(): UserResponse
    {
        return $this->userReadRepository->findAuthenticated();
    }

}
