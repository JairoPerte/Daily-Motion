import { Category, CreateCategory } from "@/models/Category/Category";

export const fetchCategories = async (
  page: number,
  limit: number
): Promise<Category[]> => {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/category?page=${page}&limit=${limit}`,
      {
        method: "GET",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (!res.ok) throw new Error("Failed to fetch categories");

    return res.json();
  } catch (err) {
    console.error(err);
    return [];
  }
};

export const createCategory = async (
  category: CreateCategory
): Promise<Category | null> => {
  try {
    const iconNumber = category.iconNumber;
    const name = category.name;

    const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/category`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ iconNumber, name }),
    });

    if (!res.ok) throw new Error("Failed to create category");

    return await res.json();
  } catch (err) {
    console.error(err);
    return null;
  }
};
