import { Session } from "@/models/Session/Session";
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

export async function fetchSessions(): Promise<Session[]> {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/sessions`,
      {
        credentials: "include",
        method: "GET",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (res.status == 200) {
      return await res.json();
    } else {
      throw new Error();
    }
  } catch (error) {
    console.error("There has been an error: ", error);
    throw new Error();
  }
}

export async function revokeSession(id: string): Promise<boolean> {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/sessions/${id}`,
      {
        credentials: "include",
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    return (await res.status) == 204;
  } catch (error) {
    console.error("There has been an error: ", error);
    throw new Error();
  }
}

export async function revokeAllSession(): Promise<boolean> {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/sessions`,
      {
        credentials: "include",
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    return (await res.status) == 204;
  } catch (error) {
    console.error("There has been an error: ", error);
    throw new Error();
  }
}

export async function logOut(): Promise<boolean> {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/auth/logout`, {
      credentials: "include",
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: null,
    });

    return (await res.status) == 204;
  } catch (error) {
    console.error("There has been an error: ", error);
    throw new Error();
  }
}

export async function deleteUser(): Promise<boolean> {
  try {
    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/auth/user`, {
      credentials: "include",
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: null,
    });

    return (await res.status) == 204;
  } catch (error) {
    console.error("There has been an error: ", error);
    throw new Error();
  }
}
