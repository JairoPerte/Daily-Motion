import { User, UserWithoutRelation } from "@/models/User/User";

export const getProfile = async (usertag: string): Promise<User> => {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/user/${usertag}`,
      {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (!res.ok) throw new Error("Failed to fetch user profile");

    return res.json();
  } catch (err) {
    console.error(err);
    throw new Error("Failed to fetch user profile");
  }
};

export const getUsers = async (
  search: string,
  page: number,
  limit: number
): Promise<UserWithoutRelation[]> => {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/users?search=${search}&page=${page}&limit=${limit}`,
      {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (!res.ok) throw new Error("Failed to fetch user list");

    return res.json();
  } catch (err) {
    console.error(err);
    throw new Error("Failed to fetch user list");
  }
};
