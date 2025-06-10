import { Activity, CreateActivity } from "@/models/Activity/Activity";

export const createActivity = async (
  activity: CreateActivity
): Promise<Activity | null> => {
  try {
    const categoryId = activity.categoryId;
    const name = activity.name;
    const startedAt = activity.startedAt;
    const finishedAt = activity.finishedAt;

    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/activity`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ categoryId, name, startedAt, finishedAt }),
    });

    if (!res.ok) throw new Error("Failed to create activity");

    return await res.json();
  } catch (err) {
    console.error(err);
    return null;
  }
};

export const listActivities = async (
  startDate: string,
  period: string,
  categoryId: string | null,
  name: string | null
): Promise<Activity[]> => {
  try {
    let urlToFetch = `${process.env.NEXT_PUBLIC_API_URL}/activity/period?period=${period}&startDate=${startDate}`;

    if (categoryId) urlToFetch = urlToFetch + `&categoryId=${categoryId}`;
    if (name) urlToFetch = urlToFetch + `&name=${name}`;

    const res = await fetch(urlToFetch, {
      method: "GET",
      headers: { "Content-Type": "application/json" },
      body: null,
    });

    if (!res.ok) throw new Error("Failed to create activity");

    return await res.json();
  } catch (err) {
    console.error(err);
    throw new Error();
  }
};
