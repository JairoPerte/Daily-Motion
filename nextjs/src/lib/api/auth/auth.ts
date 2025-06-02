import { UserLoggedIn } from "@/models/User/User";

export async function fetchUserLoggedIn(): Promise<UserLoggedIn | 0 | 1 | 2> {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/verify-user`,
      {
        credentials: "include",
        method: "GET",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (res.status == 200) {
      return await res.json();
    } else if (res.status == 403) {
      return 2;
    } else if (res.status == 401) {
      return 1;
    } else {
      return 0;
    }
  } catch (error) {
    console.error("There has been an error: ", error);
    return 0;
  }
}
