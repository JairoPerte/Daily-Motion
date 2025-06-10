"use client";

import { useEffect, useState } from "react";
import {
  getFriends,
  getFriendsRequests,
  deleteFriend,
  acceptFriendRequest,
  declineFriendRequest,
} from "@/lib/api/friend/friend";
import { UserRelation } from "@/lib/constants/user-relations";
import { FriendUser, FriendUserRequest } from "@/models/Friend/Friend";
import { ChevronDown, ChevronUp, X } from "lucide-react";
import { useRouter } from "next/navigation";

interface FriendsModalProps {
  isOpen: boolean;
  onClose: () => void;
  usertag: string;
}

export default function FriendsModal({
  isOpen,
  onClose,
  usertag,
}: FriendsModalProps) {
  const [friends, setFriends] = useState<FriendUser[]>([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [relation, setRelation] = useState<UserRelation | null>(null);
  const [requests, setRequests] = useState<FriendUserRequest[]>([]);
  const [showRequests, setShowRequests] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const router = useRouter();

  useEffect(() => {
    if (isOpen) {
      setFriends([]);
      setPage(1);
      setHasMore(true);
      setRelation(null);
      setRequests([]);
      setShowRequests(false);
    }
  }, [isOpen]);

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") onClose();
    };
    if (isOpen) window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, onClose]);

  useEffect(() => {
    if (!isOpen || !hasMore || isLoading) return;

    setIsLoading(true);
    getFriends(usertag, page, 30)
      .then((data) => {
        setFriends((prev) => [...prev, ...data.friends]);
        setRelation(data.publicUserRelation);
        if (data.friends.length === 0) {
          setHasMore(false);
        }
      })
      .catch(console.error)
      .finally(() => setIsLoading(false));
  }, [usertag, page, isOpen, hasMore, isLoading]);

  useEffect(() => {
    if (relation === UserRelation.YOURSELF) {
      getFriendsRequests()
        .then((data) => {
          if (Array.isArray(data)) setRequests(data);
          else setRequests([]);
        })
        .catch(console.error);
    }
  }, [relation]);

  const handleDeleteFriend = async (usertag: string) => {
    const success = await deleteFriend(usertag);
    if (success)
      setFriends((prev) => prev.filter((f) => f.usertag !== usertag));
  };

  const handleAccept = async (usertag: string) => {
    const success = await acceptFriendRequest(usertag);
    if (success)
      setRequests((prev) => prev.filter((r) => r.usertag !== usertag));
  };

  const handleDecline = async (usertag: string) => {
    const success = await declineFriendRequest(usertag);
    if (success)
      setRequests((prev) => prev.filter((r) => r.usertag !== usertag));
  };

  if (!isOpen) return null;

  return (
    <div className="w-full max-w-md p-6 relative">
      <h2 className="text-xl font-semibold mb-4 text-[#363f50]">
        Amigos de @{usertag}
      </h2>

      {relation === UserRelation.YOURSELF && (
        <div className="mb-4 w-full">
          <button
            onClick={() => setShowRequests((prev) => !prev)}
            className="flex items-center gap-2 text-white bg-blue-500 px-4 py-2 rounded-md w-full"
          >
            Solicitudes de amistad
            {showRequests ? <ChevronUp size={18} /> : <ChevronDown size={18} />}
          </button>

          {showRequests && (
            <ul className="mt-2 border rounded-md p-2 bg-blue-50 w-full">
              {requests.length === 0 ? (
                <li className="text-sm text-gray-600 text-center">
                  No hay solicitudes.
                </li>
              ) : (
                requests.map((r) => (
                  <li
                    key={r.usertag}
                    className="flex items-center justify-between gap-4 py-3 border-y"
                  >
                    <div
                      className="flex items-center gap-4 cursor-pointer"
                      onClick={() => router.push(`/app/profile/${r.usertag}`)}
                    >
                      <img
                        src={`${process.env.NEXT_PUBLIC_MEDIA_URL}/${r.img}`}
                        alt={r.name}
                        className="rounded-full object-cover w-12 h-12"
                      />
                      <div>
                        <p className="font-semibold text-[#363f50] text-base">
                          {r.name}
                        </p>
                        <p className="text-sm text-gray-500">@{r.usertag}</p>
                      </div>
                    </div>
                    <div className="flex gap-2">
                      <button
                        onClick={() => handleAccept(r.usertag)}
                        className="text-white bg-green-500 px-2 py-1 rounded"
                      >
                        Aceptar
                      </button>
                      <button
                        onClick={() => handleDecline(r.usertag)}
                        className="text-white bg-red-500 px-2 py-1 rounded"
                      >
                        Rechazar
                      </button>
                    </div>
                  </li>
                ))
              )}
            </ul>
          )}
        </div>
      )}

      <div className="space-y-4">
        {friends.length === 0 && !hasMore ? (
          <p className="text-center text-gray-500 text-sm">
            No tiene ningún amigo.
          </p>
        ) : (
          friends.map((friend) => (
            <div
              key={friend.usertag}
              className="flex items-center justify-between py-3 border-y cursor-pointer"
              onClick={() => router.push(`/app/profile/${friend.usertag}`)}
            >
              <div className="flex items-center gap-4">
                <img
                  src={`${process.env.NEXT_PUBLIC_MEDIA_URL}/${friend.img}`}
                  alt={friend.name}
                  className="rounded-full object-cover w-12 h-12"
                />
                <div>
                  <p className="font-semibold text-[#363f50] text-base">
                    {friend.name}
                  </p>
                  <p className="text-sm text-gray-500">@{friend.usertag}</p>
                </div>
              </div>
              {relation === UserRelation.YOURSELF && (
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    handleDeleteFriend(friend.usertag);
                  }}
                  className="text-red-500 hover:text-red-700"
                >
                  <X size={18} />
                </button>
              )}
            </div>
          ))
        )}

        {hasMore && (
          <button
            onClick={() => setPage((prev) => prev + 1)}
            className="text-sm text-blue-500 hover:underline"
            disabled={isLoading}
          >
            {isLoading ? "Cargando..." : "Cargar más"}
          </button>
        )}
      </div>
    </div>
  );
}
