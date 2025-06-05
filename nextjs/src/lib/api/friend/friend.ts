const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function sendFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/firends/send/${usertag}`, {
    method: "POST",
  });
  return res.status === 204;
}

export async function deleteFriend(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/firends/delete/${usertag}`, {
    method: "DELETE",
  });
  return res.status === 204;
}

export async function declineFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/firends/decline/${usertag}`, {
    method: "DELETE",
  });
  return res.status === 204;
}

export async function acceptFriendRequest(usertag: string): Promise<boolean> {
  const res = await fetch(`${API_URL}/user/firends/accept/${usertag}`, {
    method: "PUT",
  });
  return res.status === 204;
}
