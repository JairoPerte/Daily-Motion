"use client";

import { useRouter } from "next/navigation";
import { useEffect } from "react";

import { fetchUserLoggedIn } from "@/lib/api/auth/auth";

export default function ClientLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const router = useRouter();

  useEffect(() => {
    (async () => {
      const userData = await fetchUserLoggedIn();

      if (userData && typeof userData === "object" && "usertag" in userData) {
        router.push("/app");
      }
    })();
  }, [router]);

  return <>{children}</>;
}
