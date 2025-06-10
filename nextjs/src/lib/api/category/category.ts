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

export const deleteCategory = async (id: string): Promise<boolean> => {
  try {
    const res = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/category/${id}`,
      {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: null,
      }
    );

    if (!res.ok) throw new Error("Failed to create category");

    return (await res.status) == 204;
  } catch (err) {
    console.error(err);
    throw new Error();
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
