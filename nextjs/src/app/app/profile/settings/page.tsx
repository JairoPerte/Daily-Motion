"use client";

import { useEffect, useRef, useState } from "react";
import clsx from "clsx";
import { useRouter } from "next/navigation";

import {
  Upload,
  Pencil,
  Loader2,
  Save,
  CheckCircle,
  AlertTriangle,
  Monitor,
  Smartphone,
  HelpCircle,
  X,
  Circle,
  LogOut,
  Trash2,
} from "lucide-react";

import { Session } from "@/models/Session/Session";

import { getDevice, getDeviceInfo } from "@/lib/utils/user-agent";

import {
  fetchSessions,
  revokeSession,
  revokeAllSession,
  logOut,
  deleteUser,
} from "@/lib/api/auth/auth";

import { formatToDate } from "@/lib/utils/time";

import { fetchUserLoggedIn } from "@/lib/api/auth/auth";
import { UserLoggedIn } from "@/models/User/User";

const passwordRegex =
  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,128}$/;

export default function UserSettingsPage() {
  const router = useRouter();
  const [user, setUser] = useState<UserLoggedIn>();

  useEffect(() => {
    (async () => {
      const userData = await fetchUserLoggedIn();

      if (userData && typeof userData === "object" && "usertag" in userData) {
        setUser(userData);
      } else if (userData === 2) {
        router.push("/verify-email?message=Verifica tu email para continuar");
      } else {
        router.push("/login?message=Tu sesión ha caducado o es inválida");
      }
    })();
  }, [router]);

  const [name, setName] = useState("");
  const [usertag, setUsertag] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [oldPassword, setOldPassword] = useState("");

  const [nameError, setNameError] = useState("");
  const [usertagError, setUsertagError] = useState("");
  const [passwordError, setPasswordError] = useState("");
  const [confirmPasswordError, setConfirmPasswordError] = useState("");

  const [saving, setSaving] = useState(false);
  const [saveSuccess, setSaveSuccess] = useState(false);
  const [saveError, setSaveError] = useState("");

  const [profileImg, setProfileImg] = useState<File | null>(null);
  const [profileImgName, setProfileImgName] = useState<string | null>(null);
  const [showModal, setShowModal] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const validateFields = () => {
    let valid = true;

    if (name != "" && (name.length < 5 || name.length > 100)) {
      setNameError("El nombre debe tener entre 5 y 100 caracteres.");
      valid = false;
    } else {
      setNameError("");
    }

    if (usertag != "" && (usertag.length < 3 || usertag.length > 20)) {
      setUsertagError("El usuario debe tener entre 3 y 20 caracteres.");
      valid = false;
    } else {
      setUsertagError("");
    }

    if (newPassword) {
      if (!passwordRegex.test(newPassword)) {
        setPasswordError(
          "La contraseña debe tener entre 12 y 128 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos."
        );
        valid = false;
      } else {
        setPasswordError("");
      }

      if (newPassword !== confirmPassword) {
        setConfirmPasswordError("Las contraseñas tienen que coincidir.");
        valid = false;
      } else {
        setConfirmPasswordError("");
      }
    }

    return valid;
  };

  const handleSave = async () => {
    if (!validateFields()) return;

    setSaving(true);
    setSaveError("");
    setSaveSuccess(false);

    const formData = new FormData();
    formData.append("oldPassword", oldPassword);
    if (name) formData.append("name", name);
    if (usertag) formData.append("usertag", usertag);
    if (newPassword) formData.append("newPassword", newPassword);
    if (profileImg) formData.append("userProfileImg", profileImg);

    try {
      const res = await fetch(
        `${process.env.NEXT_PUBLIC_API_URL}/user/settings`,
        {
          method: "POST",
          body: formData,
          credentials: "include",
        }
      );

      if (res.status == 201) {
        setSaveSuccess(true);
        window.location.reload();
      } else if (res.status == 400) {
        setSaveError("Debes rellenar al menos un campo.");
      } else if (res.status == 401) {
        setSaveError("La contraseña actual es incorrecta.");
      } else if (res.status == 409) {
        setSaveError("Ya existe un usuario con ese usertag.");
      } else {
        setSaveError("No se ha podido guardar.");
      }
    } catch {
      setSaveError("Ha ocurrido un error al intentar guardar.");
    } finally {
      setSaving(false);
    }
  };

  const handleFileChange = (file: File) => {
    if (file.size > 100 * 1024 * 1024) {
      setSaveError("El archivo pesa más de 100MB.");
      return;
    }
    setProfileImg(file);
    setProfileImgName(file.name);
    setShowModal(false);
  };

  const [sessions, setSessions] = useState<Session[]>([]);
  const [showConfirmModal, setShowConfirmModal] = useState(false);

  useEffect(() => {
    const loadSessions = async () => {
      try {
        const data = await fetchSessions();
        setSessions(data);
      } catch (error) {
        console.error("Error fetching sessions:", error);
      }
    };
    loadSessions();
  }, []);

  const handleRevoke = async (id: string) => {
    const ok = await revokeSession(id);
    if (ok) {
      setSessions((prev) => prev.filter((s) => s.sessionId !== id));
    }
  };

  const handleRevokeAll = async () => {
    const ok = await revokeAllSession();
    if (ok) {
      setSessions((prev) => prev.filter((s) => s.isThisSession));
      setShowConfirmModal(false);
    }
  };

  const handleLogOut = async () => {
    const ok = await logOut();
    if (ok) {
      router.push("/login");
    }
  };

  const [showUserDelete, setUserDelete] = useState(false);

  const handleDeleteUser = async () => {
    const ok = await deleteUser();
    if (ok) {
      router.push("/register");
    }
  };

  function CountdownDeleteButton({ onConfirm }: { onConfirm: () => void }) {
    const [countdown, setCountdown] = useState(5);
    const [enabled, setEnabled] = useState(false);

    useEffect(() => {
      let timerId: ReturnType<typeof setTimeout>;

      if (countdown > 0) {
        timerId = setTimeout(() => setCountdown((prev) => prev - 1), 1000);
      } else {
        setEnabled(true);
      }

      return () => {
        if (timerId) clearTimeout(timerId);
      };
    }, [countdown]);

    return (
      <button
        onClick={() => enabled && onConfirm()}
        className={`px-4 py-2 rounded transition font-medium flex items-center gap-1 ${
          enabled
            ? "bg-red-600 text-white hover:bg-red-700"
            : "text-red-600 border border-red-600 cursor-not-allowed"
        }`}
        disabled={!enabled}
      >
        {enabled ? "Eliminar" : `Eliminar (${countdown})`}
      </button>
    );
  }

  if (!user) return null;

  return (
    <div className="max-w-3xl mx-auto bg-white rounded-2xl shadow p-8">
      <h1 className="text-2xl font-bold mb-6">Configuración del Usuario</h1>

      <div className="relative w-32 h-32 mx-auto mb-4">
        <img
          src={
            `${process.env.NEXT_PUBLIC_MEDIA_URL}/${user.img}` ||
            `${process.env.NEXT_PUBLIC_MEDIA_URL}/profile/default-profile.png`
          }
          alt="Imagen de perfil"
          className="w-full h-full object-cover rounded-full"
        />
        <div
          className="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gray-200 p-2 rounded shadow cursor-pointer"
          onClick={() => setShowModal(true)}
        >
          <Pencil className="w-4 h-4 text-gray-600" />
        </div>
      </div>

      {profileImgName && (
        <div className="text-center text-sm text-gray-600 mb-4">
          Imagen seleccionada: {profileImgName}
        </div>
      )}

      <form
        onSubmit={(e) => {
          e.preventDefault();
          handleSave();
        }}
        className="space-y-4"
      >
        <input
          type="text"
          placeholder="Nombre"
          className="w-full p-2 border rounded"
          value={name}
          onChange={(e) => setName(e.target.value)}
        />
        {nameError && <p className="text-red-500 text-sm">{nameError}</p>}
        <input
          type="text"
          placeholder="Usuario"
          className="w-full p-2 border rounded"
          value={usertag}
          onChange={(e) => setUsertag(e.target.value)}
        />
        {usertagError && <p className="text-red-500 text-sm">{usertagError}</p>}
        <input
          type="email"
          value={user.email}
          disabled
          className="w-full p-2 border rounded bg-gray-100"
        />
        <input
          type="password"
          placeholder="Nueva contraseña"
          className="w-full p-2 border rounded"
          value={newPassword}
          onChange={(e) => setNewPassword(e.target.value)}
        />
        {passwordError && (
          <p className="text-red-500 text-sm">{passwordError}</p>
        )}
        <input
          type="password"
          placeholder="Confirmar nueva contraseña"
          className="w-full p-2 border rounded"
          value={confirmPassword}
          onChange={(e) => setConfirmPassword(e.target.value)}
        />
        {confirmPasswordError && (
          <p className="text-red-500 text-sm">{confirmPasswordError}</p>
        )}
        <input
          type="password"
          placeholder="Contraseña actual"
          className="w-full p-2 border rounded"
          value={oldPassword}
          onChange={(e) => setOldPassword(e.target.value)}
          required
        />
        <button
          type="submit"
          className={clsx(
            "w-full flex items-center justify-center gap-2 text-white font-bold py-2 px-4 rounded",
            !oldPassword || saving
              ? "bg-gray-400 cursor-not-allowed"
              : "bg-[#2F855A] hover:bg-green-700"
          )}
        >
          {saving ? (
            <>
              <Loader2 className="animate-spin w-4 h-4" /> Guardando...
            </>
          ) : saveSuccess ? (
            <>
              <CheckCircle className="w-4 h-4" /> Guardado
            </>
          ) : (
            <>
              <Save className="w-4 h-4" /> Guardar
            </>
          )}
        </button>
        {saveError && (
          <div className="mt-2 text-red-600 flex items-center gap-2">
            <AlertTriangle className="w-4 h-4" />
            {saveError}
          </div>
        )}
      </form>

      {/* Acciones */}
      <div className="mt-6 flex justify-between gap-4">
        <button
          onClick={handleLogOut}
          className="flex items-center gap-2 px-4 py-2 text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200"
        >
          <LogOut className="w-4 h-4" />
          Cerrar sesión
        </button>

        <button
          onClick={() => setUserDelete(true)}
          className="flex items-center gap-2 px-4 py-2 text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200"
        >
          <Trash2 className="w-4 h-4" />
          Eliminar Cuenta
        </button>
      </div>

      {showUserDelete && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <button
              onClick={() => setUserDelete(false)}
              className="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
              aria-label="Cerrar"
            >
              ✕
            </button>

            <h2 className="text-xl font-semibold mb-4 text-center text-red-600">
              ¿Estás seguro de que quieres eliminar tu cuenta?
            </h2>

            <p className="text-sm text-gray-600 mb-6 text-center">
              Una vez que elimines tu cuenta{" "}
              <strong>no hay vuelta atrás</strong>.
            </p>

            <div className="flex justify-end gap-4">
              <button
                onClick={() => setUserDelete(false)}
                className="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-100 transition"
              >
                Cancelar
              </button>

              <CountdownDeleteButton onConfirm={handleDeleteUser} />
            </div>
          </div>
        </div>
      )}

      {/* Sesiones */}
      <div className="mt-4 space-y-4">
        <h1 className="text-2xl font-bold mb-6">Seiones Activas</h1>

        {sessions.map((session) => (
          <div
            key={session.sessionId}
            className={clsx(
              "flex items-center justify-between p-4 border rounded-lg",
              session.isThisSession ? "border-green-600" : "border-gray-200"
            )}
          >
            <div className="flex items-start gap-4">
              <div>
                {getDevice(session.userAgent) == 1 ? (
                  <Smartphone className="w-6 h-6 text-gray-500" />
                ) : getDevice(session.userAgent) == 2 ? (
                  <Monitor className="w-6 h-6 text-gray-500" />
                ) : (
                  <HelpCircle className="w-6 h-6 text-gray-500" />
                )}
              </div>
              <div>
                <div className="text-[#363f50] font-medium">
                  {getDeviceInfo(session.userAgent)}
                </div>
                <div className="text-sm text-gray-500">
                  {session.isThisSession ? (
                    <span className="flex items-center gap-1 text-green-600 font-medium">
                      <Circle className="w-2 h-2 fill-green-600" /> Sesión
                      Actual
                    </span>
                  ) : (
                    `Última conexión: ${formatToDate(session.lastActivity)}`
                  )}
                </div>
              </div>
            </div>
            <div>
              {session.isThisSession ? (
                <button
                  onClick={() => setShowConfirmModal(true)}
                  className="border border-red-500 text-red-500 px-3 py-1 rounded hover:bg-red-500 hover:text-white text-sm"
                >
                  Cerrar otros dispositivos
                </button>
              ) : (
                <button
                  onClick={() => handleRevoke(session.sessionId)}
                  className="text-gray-400 hover:text-red-600"
                  title="Cerrar sesión"
                >
                  <X className="w-4 h-4" />
                </button>
              )}
            </div>
          </div>
        ))}

        {sessions.length > 1 && (
          <div className="mt-4 border-t border-gray-200" />
        )}
      </div>

      {/* Modal de sessiones */}
      {showConfirmModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <button
              onClick={() => setShowConfirmModal(false)}
              className="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
              aria-label="Cerrar"
            >
              ✕
            </button>

            <h2 className="text-xl font-semibold mb-4 text-center">
              ¿Cerrar sesión en los otros dispositivos?
            </h2>
            <p className="text-sm text-gray-600 mb-6 text-center">
              Esta acción cerrará la sesión en todos los dispositivos excepto en
              este.
            </p>

            <div className="flex justify-end gap-2">
              <button
                onClick={() => setShowConfirmModal(false)}
                className="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
              >
                Cancelar
              </button>
              <button
                onClick={handleRevokeAll}
                className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Aceptar
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Modal de imagen */}
      {showModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div className="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            {/* Botón para cerrar */}
            <button
              onClick={() => setShowModal(false)}
              className="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
              aria-label="Cerrar"
            >
              ✕
            </button>

            {/* Título */}
            <h2 className="text-xl font-semibold mb-4 text-center">
              Selecciona una Imagen
            </h2>

            <div
              className="w-full h-48 p-6 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-center text-gray-600 cursor-pointer"
              onDrop={(e) => {
                e.preventDefault();
                const file = e.dataTransfer.files?.[0];
                if (file) handleFileChange(file);
              }}
              onDragOver={(e) => e.preventDefault()}
              onClick={() => fileInputRef.current?.click()}
            >
              <Upload className="mx-auto w-12 h-12 text-gray-500" />
              <p className="my-4">
                Arrastra aquí el archivo o haz clic para seleccionarlo
              </p>
              <input
                type="file"
                ref={fileInputRef}
                hidden
                accept="image/*"
                onChange={(e) => {
                  const file = e.target.files?.[0];
                  if (file) handleFileChange(file);
                }}
              />
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
