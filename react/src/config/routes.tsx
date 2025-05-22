import { BrowserRouter, Routes, Route } from "react-router-dom";
import App from "@/presentation/App";
import { LoginPage } from "@/presentation/pages/LoginPage";

export function AppRoutes() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<App />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<LoginPage />} />
      </Routes>
    </BrowserRouter>
  );
}
