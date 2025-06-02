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
