"use client";
import { useState } from "react";
import { useRouter } from "next/navigation";
import { FaBars, FaSearch } from "react-icons/fa";
import { UserLoggedIn } from "@/models/User/User";

export default function Navbar({
  toggleSidebar,
  user,
}: {
  toggleSidebar: () => void;
  user?: UserLoggedIn;
}) {
  const [searchOpen, setSearchOpen] = useState(false);
  const [query, setQuery] = useState("");
  const router = useRouter();

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) router.push(`/app/search?q=${encodeURIComponent(query)}`);
  };

  const handleProfileClick = () => {
    if (user) {
      router.push(`/app/profile/${user.usertag}`);
    }
  };

  return (
    <nav className="bg-[#256545] text-white flex items-center justify-between p-3 relative">
      {/* Left: Menu Icon */}
      <button onClick={toggleSidebar} className="text-2xl">
        <FaBars />
      </button>

      {/* Center: Search */}
      <div className="hidden md:flex items-center w-1/2">
        <form onSubmit={handleSearchSubmit} className="relative w-full">
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Buscar..."
            className="w-full px-10 py-1 rounded bg-[#ffffff] text-black"
          />
          <FaSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-black" />
        </form>
      </div>
      <button onClick={() => setSearchOpen(!searchOpen)} className="md:hidden">
        <FaSearch />
      </button>

      {/* Right: Profile */}
      <div
        onClick={handleProfileClick}
        className="flex items-center gap-2 cursor-pointer"
      >
        <img
          src={`${process.env.NEXT_PUBLIC_MEDIA_URL}/${
            user?.img || `/profile/default.png`
          }`}
          alt="profile icon"
          className="w-8 h-8 rounded-full"
        />
        <span className="hidden md:block">{user?.usertag || "Invitado"}</span>
      </div>

      {/* Mobile search input */}
      {searchOpen && (
        <form
          onSubmit={handleSearchSubmit}
          className="absolute left-0 top-full w-full bg-[#256545] p-2"
        >
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Buscar..."
            className="w-full px-4 py-2 rounded border border-gray-300 text-black"
          />
        </form>
      )}
    </nav>
  );
}
