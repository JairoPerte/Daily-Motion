// components/selects/IconSelect.tsx
"use client";

import { useState } from "react";
import { icons } from "@/lib/constants/icons";

type Props = {
  value: number;
  onChange: (id: number) => void;
};

export default function IconSelect({ value, onChange }: Props) {
  const [open, setOpen] = useState(false);
  const selectedIcon = icons.find((i) => i.id === value)?.icon;

  return (
    <div className="relative w-full">
      <button
        onClick={() => setOpen((prev) => !prev)}
        className="w-full border px-4 py-2 rounded bg-white shadow flex items-center gap-2"
      >
        {selectedIcon}
        <span className="text-gray-600">Selecciona un ícono</span>
      </button>

      {open && (
        <div className="absolute mt-1 w-full max-h-48 overflow-y-auto bg-white border shadow-md rounded z-20">
          {icons.map((icon) => (
            <div
              key={icon.id}
              onClick={() => {
                onChange(icon.id);
                setOpen(false);
              }}
              className="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2"
            >
              {icon.icon}
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
