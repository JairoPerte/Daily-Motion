import { useState } from "react";

import { CreateCategory } from "@/models/Category/Category";
import { createCategory } from "@/lib/api/category/category";

import IconSelect from "@/presentation/components/selects/IconSelect";

type ModalProps = {
  isOpen: boolean;
  onClose: () => void;
  onChange: (id: string) => void;
};

export default function CategoryModal({
  isOpen,
  onClose,
  onChange,
}: ModalProps) {
  const MAX_CHARS = 100;

  const [newName, setNewName] = useState("");
  const [newIcon, setNewIcon] = useState(1);

  const isValidInput = newName.length > 0 && newName.length <= MAX_CHARS;

  const handleCreate = async () => {
    const newCategory: CreateCategory = {
      iconNumber: newIcon,
      name: newName,
    };
    const created = await createCategory(newCategory);
    if (created) {
      onChange(created.id);
      onClose();
      setNewName("");
      setNewIcon(1);
    }
  };

  return (
    <>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            {/* Botón para cerrar */}
            <button
              onClick={onClose}
              className="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
              aria-label="Cerrar"
            >
              ✕
            </button>

            {/* Título */}
            <h2 className="text-xl font-semibold mb-4">Crear una Categoría</h2>

            {/* Contenido del modal */}
            <div className="space-y-4">
              <input
                type="text"
                placeholder="Nombre"
                value={newName}
                onChange={(e) => setNewName(e.target.value)}
                className="w-full border px-3 py-2 rounded"
              />
              <IconSelect value={newIcon} onChange={setNewIcon} />
              <button
                className={`mt-4 px-6 py-3 rounded text-white text-lg font-semibold transition ${
                  isValidInput
                    ? "bg-[#2F855A] hover:bg-[#276749]"
                    : "bg-gray-300 cursor-not-allowed"
                }`}
                disabled={!isValidInput}
                onClick={handleCreate}
              >
                Crear
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
