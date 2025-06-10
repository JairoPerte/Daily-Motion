"use client";

import { Suspense } from "react";
import UserSearchPage from "@/app/app/search/UserSearchPage";

export default function UserSearch() {
  return (
    <Suspense fallback={<div>Cargando búsqueda...</div>}>
      <UserSearchPage />
    </Suspense>
  );
}
