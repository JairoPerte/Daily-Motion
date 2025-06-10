"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";
// import { AlertTriangle, X } from "lucide-react";

import { fetchUserLoggedIn } from "@/lib/api/auth/auth";

export default function ClientLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const router = useRouter();
  // const searchParams = useSearchParams();

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
        router.push("/app");
      }
    })();
  }, [router]);

  return (
    <>
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

      {children}
    </>
  );
}
