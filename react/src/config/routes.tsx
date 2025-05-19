import { BrowserRouter, Routes, Route } from "react-router-dom";
import { LoginPage } from "@/presentation/pages/LoginPage";

export function AppRoutes() {
  return (
    <BrowserRouter basename="/app">
      {" "}
      {/* CRUCIAL si tu app está montada en /app */}
      <Routes>
        <Route path="/" element={<LoginPage />} />
      </Routes>
    </BrowserRouter>
  );
}
