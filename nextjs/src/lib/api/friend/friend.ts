import { FriendUserRequest, ListFriendsUser } from "@/models/Friend/Friend";

const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function sendFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/friends/send/${usertag}`, {
    method: "POST",
  });
  return res.status === 204;
}

export async function deleteFriend(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/friends/delete/${usertag}`, {
    method: "DELETE",
  });
  return res.status === 204;
}

export async function declineFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/friends/decline/${usertag}`, {
    method: "DELETE",
  });
  return res.status === 204;
}

export async function acceptFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/friends/accept/${usertag}`, {
    method: "PUT",
  });
  return res.status === 204;
}

export async function getFriends(
  usertag: string,
  page: number,
  limit: number
): Promise<ListFriendsUser> {
  const res = await fetch(
    `${API_URL}/user/${usertag}/friends?page=${page}&limit=${limit}`,
    {
      method: "GET",
    }
  );
  if (!res.ok) throw new Error("Failed to fetch friends");
  return res.json();
}

export async function getFriendsRequests(): Promise<FriendUserRequest[]> {
  const res = await fetch(`${API_URL}/user/friends/requests`, {
    method: "GET",
  });
  if (!res.ok) throw new Error("Failed to fetch friend requests");
  return res.json();
}
