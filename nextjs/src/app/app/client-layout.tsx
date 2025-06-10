"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
// import { AlertTriangle, X } from "lucide-react";

import Navbar from "@/presentation/components/navigation/navbar/navbars";
import Sidebar from "@/presentation/components/navigation/sidebar/sidebars";

import { fetchUserLoggedIn } from "@/lib/api/auth/auth";
import { UserLoggedIn } from "@/models/User/User";

export default function ClientLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const router = useRouter();
  // const searchParams = useSearchParams();

  const [sidebarOpen, setSidebarOpen] = useState(true);

  const [user, setUser] = useState<UserLoggedIn>();

  // const [message, setMessage] = useState("");

  // const [showAlert, setShowAlert] = useState(false);

  // useEffect(() => {
  //   const msg = searchParams.get("message");
  //   if (msg) {
  //     setMessage(msg);
  //     setShowAlert(true);

  //     const timeout = setTimeout(() => {
  //       setShowAlert(false);
  //     }, 5000);

  //     return () => clearTimeout(timeout);
  //   }
  // }, [searchParams]);

  useEffect(() => {
    (async () => {
      const userData = await fetchUserLoggedIn();

      if (userData && typeof userData === "object" && "usertag" in userData) {
        setUser(userData);
      } else if (userData === 2) {
        router.push("/verify-email?message=Verifica tu email para continuar");
      } else if (userData === 1) {
        router.push("/login?message=Tu sesión ha caducado o es inválida");
      } else if (userData === 0) {
        router.push("/login?message=Tu sesión ha caducado o es inválida");
      } else {
        router.push("/login?message=Tu sesión ha caducado o es inválida");
      }
    })();
  }, [router]);

  return (
    <div className="h-screen flex flex-col">
      {/* {showAlert && (
        <div className="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg flex items-center justify-between gap-4 w-[90%] max-w-xl">
          <div className="flex items-center gap-2">
            <AlertTriangle className="w-5 h-5 text-red-700" />
            <span className="text-sm font-medium">{message}</span>
          </div>
          <button
            onClick={() => setShowAlert(false)}
            className="text-red-700 hover:text-red-900"
          >
            <X className="w-4 h-4" />
          </button>
        </div>
      )} */}
      <Navbar toggleSidebar={() => setSidebarOpen(!sidebarOpen)} user={user} />
      <div className="flex flex-1">
        {sidebarOpen && (
          <Sidebar
            sidebarOpen={sidebarOpen}
            toggleSidebar={() => setSidebarOpen(false)}
          />
        )}
        <main className="flex-1 p-6">{children}</main>
      </div>
    </div>
  );
}
