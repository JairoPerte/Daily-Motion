"use client";

import { Suspense } from "react";
import WeekActivityGraph from "@/app/app/activities/GraphActivity";

export default function UserSearch() {
  return (
    <Suspense fallback={<div>Cargando actividades...</div>}>
      <WeekActivityGraph />
    </Suspense>
  );
}
