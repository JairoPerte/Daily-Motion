"use client";

import { useCallback, useEffect, useRef, useState } from "react";
import { useRouter } from "next/navigation";
import { FaTrash } from "react-icons/fa";

import { fetchCategories, deleteCategory } from "@/lib/api/category/category";
import { Category } from "@/models/Category/Category";
import { icons } from "@/lib/constants/icons";

export default function CategoriesPage() {
  const router = useRouter();

  const [categories, setCategories] = useState<Category[]>([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [loading, setLoading] = useState(false);

  const sentinelRef = useRef<HTMLDivElement>(null);

  const fetchMore = useCallback(() => {
    if (loading || !hasMore) return;
    setLoading(true);
    fetchCategories(page, 15).then((newCategories) => {
      if (newCategories.length === 0) setHasMore(false);
      else {
        setCategories((prev) => [...prev, ...newCategories]);
        setPage((prev) => prev + 1);
      }
      setLoading(false);
    });
  }, [loading, hasMore, page]);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting) fetchMore();
      },
      {
        root: null,
        threshold: 0.5,
      }
    );
    if (sentinelRef.current) observer.observe(sentinelRef.current);
    return () => observer.disconnect();
  }, [fetchMore]);

  const handleOnDeleteCategory = async (id: string) => {
    const ok = confirm("¿Seguro que quieres eliminar esta categoría?");
    if (!ok) return;
    try {
      const response = await deleteCategory(id);
      if (response) setCategories((prev) => prev.filter((c) => c.id !== id));
    } catch (err) {
      console.error("Error al eliminar categoría", err);
    }
  };

  return (
    <div className="max-w-2xl mx-auto px-4 py-6 text-[#363f50]">
      <div className="space-y-3 max-h-[75vh] p-4">
        {categories.map((cat) => {
          const Icon = icons.find((i) => i.id === cat.iconNumber)?.icon;

          return (
            <div
              key={cat.id}
              className="flex items-center justify-between bg-white shadow rounded p-3 cursor-pointer hover:bg-[#e0dddd]"
              onClick={() => {
                router.push(`/app/activities?category=${cat.id}`);
              }}
            >
              <div className="flex items-center gap-3">
                {Icon}
                <span className="text-sm font-medium">{cat.name}</span>
              </div>
              <button
                onClick={() => handleOnDeleteCategory(cat.id)}
                className="text-red-500 hover:text-red-700"
              >
                <FaTrash />
              </button>
            </div>
          );
        })}
        {hasMore && (
          <div
            ref={sentinelRef}
            className="text-center text-sm text-[#363f50] py-3"
          >
            Cargando más...
          </div>
        )}
      </div>
    </div>
  );
}
