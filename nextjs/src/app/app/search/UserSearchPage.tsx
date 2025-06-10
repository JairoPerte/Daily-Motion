"use client";

import { useEffect, useRef, useState, useCallback } from "react";
import { useSearchParams, useRouter } from "next/navigation";
import { getUsers } from "@/lib/api/user/user";
import { UserWithoutRelation } from "@/models/User/User";
import { motion } from "framer-motion";
import { formatToDate } from "@/lib/utils/time";

export default function UserSearchPage() {
  const searchParams = useSearchParams();
  const router = useRouter();

  const search = searchParams.get("q") || "";

  const [users, setUsers] = useState<UserWithoutRelation[]>([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [loading, setLoading] = useState(false);

  const sentinelRef = useRef<HTMLDivElement>(null);

  const fetchMore = useCallback(async () => {
    if (loading || !hasMore) return;
    setLoading(true);
    try {
      const newUsers = await getUsers(search, page, 25);
      if (newUsers.length === 0) setHasMore(false);
      else {
        setUsers((prev) => [...prev, ...newUsers]);
        setPage((prev) => prev + 1);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  }, [search, page, loading, hasMore]);

  useEffect(() => {
    if (!sentinelRef.current) return;
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting) fetchMore();
      },
      { threshold: 1 }
    );
    observer.observe(sentinelRef.current);
    return () => observer.disconnect();
  }, [fetchMore]);

  useEffect(() => {
    setUsers([]);
    setPage(1);
    setHasMore(true);
  }, [search]);

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <h1 className="text-2xl font-bold text-[#363f50] mb-4">
        Resultados para: &quot;{search}&quot;
      </h1>

      <div className="flex flex-col gap-4">
        {users.map((user, index) => (
          <motion.div
            key={user.usertag}
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: index * 0.1 }}
            className="cursor-pointer bg-white text-[#363f50] p-6 rounded-xl shadow-md flex flex-col md:flex-row items-center gap-6 hover:shadow-lg"
            onClick={() => router.push(`/app/profile/${user.usertag}`)}
          >
            <div className="relative text-center">
              <img
                src={`${process.env.NEXT_PUBLIC_MEDIA_URL}/${user.img}`}
                alt={user.name}
                width={128}
                height={128}
                className="rounded-full object-cover w-32 h-32"
              />
            </div>

            <div className="flex-1 flex flex-col justify-center gap-2">
              <h1 className="text-2xl font-bold">{user.name}</h1>
              <p className="text-sm text-gray-500">@{user.usertag}</p>
            </div>

            <div className="text-right text-sm text-gray-500">
              Usuario creado: {formatToDate(user.createdAt)}
            </div>
          </motion.div>
        ))}

        {hasMore && (
          <div
            ref={sentinelRef}
            className="text-center text-sm text-gray-400 py-4"
          >
            Cargando más usuarios...
          </div>
        )}

        {!hasMore && users.length === 0 && (
          <div className="text-center text-gray-500">
            No se encontraron usuarios.
          </div>
        )}
      </div>
    </div>
  );
}
