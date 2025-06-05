import { UserRelation } from "@/lib/constants/user-relations";

export interface UserLoggedIn {
  name: string;
  usertag: string;
  img: string;
  email: string;
  userCreatedAt: string;
}

export interface User {
  name: string;
  usertag: string;
  img: string;
  userCreatedAt: string;
  relation: UserRelation;
}
