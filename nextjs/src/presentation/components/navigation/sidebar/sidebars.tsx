import { useRouter } from "next/navigation";
import { FaHome, FaChartLine, FaTags, FaTimes } from "react-icons/fa";
import { useEffect } from "react";

const menuItems = [
  { name: "Hogar", icon: <FaHome />, path: "/app/" },
  {
    name: "Registro de actividad",
    icon: <FaChartLine />,
    path: "/app/activities",
  },
  { name: "Categorías", icon: <FaTags />, path: "/app/categories" },
];

type Props = {
  sidebarOpen: boolean;
  toggleSidebar: () => void;
};

export default function Sidebar({ sidebarOpen, toggleSidebar }: Props) {
  const router = useRouter();

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 768 && sidebarOpen) {
        toggleSidebar();
      }
    };
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, [sidebarOpen, toggleSidebar]);

  return (
    <>
      {/* Overlay en móvil */}
      {sidebarOpen && (
        <div
          onClick={toggleSidebar}
          className="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
        />
      )}

      {/* Sidebar */}
      <aside
        className={`
    fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 z-50 bg-[#68d391] shadow-lg
    transform transition-transform duration-300 ease-in-out
    ${sidebarOpen ? "translate-x-0" : "-translate-x-full"}
    md:static md:translate-x-0 md:w-64 md:top-0 md:h-auto
  `}
      >
        {/* Botón de cerrar en móvil */}
        <div className="md:hidden flex justify-end p-4">
          <button onClick={toggleSidebar}>
            <FaTimes className="text-2xl text-gray-700" />
          </button>
        </div>

        {/* Menú */}
        <ul className="p-4 space-y-2">
          {menuItems.map((item) => (
            <li
              key={item.name}
              className="flex items-center gap-3 p-3 hover:bg-gray-100 cursor-pointer text-[#4A5568]"
              onClick={() => {
                router.push(item.path);
                toggleSidebar();
              }}
            >
              <span className="text-xl">{item.icon}</span>
              <span className="text-sm font-medium">{item.name}</span>
            </li>
          ))}
        </ul>
      </aside>
    </>
  );
}
