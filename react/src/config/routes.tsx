import { BrowserRouter, Routes, Route } from "react-router-dom";
import App from "@/presentation/App";
import { LoginPage } from "@/presentation/pages/LoginPage";

export function AppRoutes() {
  return (
    <BrowserRouter basename="/app">
      {" "}
      {/* CRUCIAL si tu app está montada en /app */}
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/login" element={<LoginPage />} />
      </Routes>
    </BrowserRouter>
  );
}
