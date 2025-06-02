"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";

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

  const [sidebarOpen, setSidebarOpen] = useState(true);

  const [user, setUser] = useState<UserLoggedIn>();

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
