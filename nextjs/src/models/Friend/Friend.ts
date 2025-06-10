import { UserRelation } from "@/lib/constants/user-relations";

export interface FriendUser {
  name: string;
  usertag: string;
  img: string;
  friendsAcceptedAt: string;
}

export interface FriendUserRequest {
  name: string;
  usertag: string;
  img: string;
}

export interface ListFriendsUser {
  publicUserRelation: UserRelation;
  friends: FriendUser[];
}
