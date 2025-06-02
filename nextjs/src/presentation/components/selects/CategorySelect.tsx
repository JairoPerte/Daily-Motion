"use client";

import { useEffect, useState, useRef, useCallback } from "react";
import { fetchCategories } from "@/lib/api/category/category";
import { Category } from "@/models/Category/Category";
import { icons } from "@/lib/constants/icons";
import { FaPen } from "react-icons/fa";

interface Props {
  value: string;
  onChange: (id: string) => void;
}

export default function CategorySelect({ value, onChange }: Props) {
  const [categories, setCategories] = useState<Category[]>([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [loading, setLoading] = useState(false);
  const [openDropdown, setOpenDropdown] = useState(false);

  const dropdownRef = useRef<HTMLDivElement>(null);
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
    if (!openDropdown || !sentinelRef.current) return;
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting) fetchMore();
      },
      { root: dropdownRef.current, threshold: 1 }
    );
    observer.observe(sentinelRef.current);
    return () => observer.disconnect();
  }, [openDropdown, fetchMore]);

  const selected = categories.find((c) => c.id === value);
  const selectedIcon = icons.find((i) => i.id === selected?.iconNumber)?.icon;

  return (
    <div className="relative w-full">
      <button
        onClick={() => setOpenDropdown((prev) => !prev)}
        className="w-full text-left pl-4 pr-4 py-2 border rounded shadow relative bg-white"
      >
        {selected ? (
          <span className="flex items-center gap-2">
            {selectedIcon} {selected.name}
          </span>
        ) : (
          <span className="flex items-center gap-2">
            <FaPen /> Crear categoría
          </span>
        )}
      </button>

      {openDropdown && (
        <div
          ref={dropdownRef}
          className="absolute z-10 mt-1 w-full max-h-60 overflow-y-auto border bg-white shadow-lg rounded"
        >
          <div
            onClick={() => {
              setOpenDropdown(false);
            }}
            className="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2"
          >
            <span
              className="text-sm flex items-center gap-2"
              onClick={() => {
                onChange("default");
                setOpenDropdown(false);
              }}
            >
              <FaPen /> Crear categoría
            </span>
          </div>
          <div className="border-t my-1" />
          {categories.map((cat) => {
            const Icon = icons.find((i) => i.id === cat.iconNumber)?.icon;
            return (
              <div
                key={cat.id}
                onClick={() => {
                  onChange(cat.id);
                  setOpenDropdown(false);
                }}
                className="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2 text-sm"
              >
                {Icon} {cat.name}
              </div>
            );
          })}
          {hasMore && (
            <div
              ref={sentinelRef}
              className="flex justify-center py-2 text-sm text-gray-400"
            >
              Cargando...
            </div>
          )}
        </div>
      )}
    </div>
  );
}
