"use client";

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import { UserRelation } from "@/lib/constants/user-relations";
import { getProfile } from "@/lib/api/user/user";

import { User } from "@/models/User/User";
import {
  sendFriendRequest,
  acceptFriendRequest,
  declineFriendRequest,
  deleteFriend,
} from "@/lib/api/friend/friend";
import { Settings, Check, X, UserPlus, Loader2 } from "lucide-react";
import FriendsModal from "@/presentation/components/modals/FriendsModal";

export default function ProfileHeader() {
  const { usertag } = useParams();
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [relation, setRelation] = useState<UserRelation | null>(null);
  const [loading, setLoading] = useState(false);
  const [showConfirmDelete, setShowConfirmDelete] = useState(false);
  const [showFriendsModal, setShowFriendsModal] = useState(false);

  useEffect(() => {
    async function fetchUser() {
      try {
        const fetchedUser = await getProfile(usertag as string);
        setUser(fetchedUser);
        setRelation(fetchedUser.relation);
      } catch (err) {
        console.error("Error fetching user", err);
      }
    }
    fetchUser();
  }, [usertag]);

  const handleSendRequest = async () => {
    setLoading(true);
    const ok = await sendFriendRequest(usertag as string);
    if (ok) setRelation(UserRelation.WAITING);
    setLoading(false);
  };

  const handleAccept = async () => {
    setLoading(true);
    const ok = await acceptFriendRequest(usertag as string);
    if (ok) setRelation(UserRelation.FRIENDS);
    setLoading(false);
  };

  const handleDecline = async () => {
    setLoading(true);
    const ok = await declineFriendRequest(usertag as string);
    if (ok) setRelation(UserRelation.STRANGERS);
    setLoading(false);
  };

  const handleDelete = async () => {
    setLoading(true);
    const ok = await deleteFriend(usertag as string);
    if (ok) setRelation(UserRelation.STRANGERS);
    setShowConfirmDelete(false);
    setLoading(false);
  };

  const handleCancelRequest = async () => {
    setLoading(true);
    const ok = await deleteFriend(usertag as string);
    if (ok) setRelation(UserRelation.STRANGERS);
    setLoading(false);
  };

  if (!user) return <div className="text-center py-10">Cargando perfil...</div>;

  return (
    <div className="bg-white text-[#363f50] p-6 rounded-xl shadow-md flex flex-col md:flex-row items-center gap-6">
      <div className="relative text-center">
        <img
          src={`${process.env.NEXT_PUBLIC_MEDIA_URL}/${user.img}`}
          alt={user.name}
          width={128}
          height={128}
          className="rounded-full object-cover w-32 h-32"
        />
        <p
          className="text-sm text-blue-600 underline mt-2 cursor-pointer"
          onClick={() => setShowFriendsModal(true)}
        >
          Amigos
        </p>
        {showFriendsModal && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
              <button
                onClick={() => setShowFriendsModal(false)}
                className="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                aria-label="Cerrar"
              >
                ✕
              </button>
              <FriendsModal
                isOpen={showFriendsModal}
                usertag={user.usertag}
                onClose={() => setShowFriendsModal(false)}
              />
            </div>
          </div>
        )}
      </div>

      <div className="flex-1 flex flex-col justify-center gap-2">
        <h1 className="text-2xl font-bold">{user.name}</h1>
        <p className="text-sm text-gray-500">@{user.usertag}</p>
      </div>

      <div className="flex gap-2 items-center">
        {relation === UserRelation.YOURSELF && (
          <button
            onClick={() => router.push("/app/profile/settings")}
            className="border border-gray-300 px-4 py-2 rounded"
          >
            <Settings className="w-5 h-5 hover:rotate-180 transition-transform" />
          </button>
        )}

        {relation === UserRelation.FRIENDS &&
          (!showConfirmDelete ? (
            <button
              className="bg-green-600 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center gap-2"
              onClick={() => setShowConfirmDelete(true)}
            >
              <Check className="w-4 h-4" /> Amigos
            </button>
          ) : (
            <div className="bg-white border p-4 rounded shadow max-w-xs">
              <p className="text-sm">
                Una vez que elimines a este amigo, tendrás que volver a enviarle
                una solicitud si quieres ser amigo de nuevo. ¿Estás seguro?
              </p>
              <div className="flex justify-end gap-2 mt-4">
                <button
                  className="text-gray-700 px-4 py-2 rounded hover:bg-gray-100"
                  onClick={() => setShowConfirmDelete(false)}
                >
                  Cancelar
                </button>
                <button
                  className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                  onClick={handleDelete}
                >
                  Aceptar
                </button>
              </div>
            </div>
          ))}

        {relation === UserRelation.STRANGERS && (
          <button
            onClick={handleSendRequest}
            disabled={loading}
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50 flex items-center gap-2"
          >
            {loading ? (
              <Loader2 className="animate-spin w-4 h-4" />
            ) : (
              <UserPlus className="w-4 h-4" />
            )}
            Enviar solicitud
          </button>
        )}

        {relation === UserRelation.PENDING && (
          <>
            <button
              onClick={handleAccept}
              disabled={loading}
              className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2"
            >
              <Check className="w-4 h-4" /> Aceptar
            </button>
            <button
              onClick={handleDecline}
              disabled={loading}
              className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 flex items-center gap-2"
            >
              <X className="w-4 h-4" /> Rechazar
            </button>
          </>
        )}

        {relation === UserRelation.WAITING && (
          <button
            onClick={handleCancelRequest}
            disabled={loading}
            className="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 disabled:opacity-50"
          >
            {loading ? (
              <Loader2 className="animate-spin w-4 h-4" />
            ) : (
              "Solicitud enviada (Cancelar)"
            )}
          </button>
        )}
      </div>
    </div>
  );
}
