<?php

namespace App\User\Application\Service;

use App\User\Application\UseCase\UserFriends\PublicFriend;
use App\User\Application\UseCase\Common\PublicUserRelation;
use App\User\Application\UseCase\UserFriends\UserFriendsPublic;
use App\User\Domain\Entity\FriendWithUser;
use App\User\Domain\Entity\User;

class FriendsToUserFriendsPublic
{
    /**
     * @param FriendWithUser[] $friends
     */
    public function __invoke(array $friends, User $user, string $visitorId): UserFriendsPublic
    {
        $relation = PublicUserRelation::STRANGERS;

        if ($user->getId() == $visitorId) {
            $relation = PublicUserRelation::YOURSELF;
        }

        $publicFriends = [];
        foreach ($friends as $friend) {
            $publicFriends[] = new PublicFriend(
                name: $friend->user->getUserName(),
                usertag: $friend->user->getUserTag(),
                img: $friend->user->getImg(),
                friendsAcceptedAt: $friend->friend->getFriendAcceptAt()
            );
        }

        return  new UserFriendsPublic(
            friends: $publicFriends,
            publicUserRelation: $relation
        );
    }
}
