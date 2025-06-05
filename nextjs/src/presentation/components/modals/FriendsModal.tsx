"use client";

import { useEffect } from "react";

type FriendsModalProps = {
  isOpen: boolean;
  onClose: () => void;
  usertag: string;
};

export default function FriendsModal({
  isOpen,
  onClose,
  usertag,
}: FriendsModalProps) {
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        onClose();
      }
    };
    if (isOpen) window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, onClose]);

  if (!isOpen) return null;

  return (
    <div className="w-full max-w-md p-6 relative">
      {/* Título */}
      <h2 className="text-xl font-semibold mb-4 text-[#363f50]">
        Amigos de @{usertag}
      </h2>

      {/* Contenido del modal */}
      <div className="text-[#363f50]">
        <p>Contenido próximamente...</p>
      </div>
    </div>
  );
}
