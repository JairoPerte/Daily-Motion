import { UserRelation } from "@/lib/constants/user-relations";

export interface UserLoggedIn {
  name: string;
  usertag: string;
  img: string;
  email: string;
  createdAt: string;
}

export interface User {
  name: string;
  usertag: string;
  img: string;
  createdAt: string;
  relation: UserRelation;
}

export interface UserWithoutRelation {
  name: string;
  usertag: string;
  img: string;
  createdAt: string;
}
