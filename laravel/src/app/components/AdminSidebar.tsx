import { type Page, type AppUser } from "../App";

interface Props {
  page: Page;
  user: AppUser | null;
  navigate: (p: Page) => void;
  logout: () => void;
}

const NAV = [
  { label: "Dashboard",  icon: "📊", page: "admin" as Page },
  { label: "Rooms",      icon: "🚪", page: "admin-rooms" as Page },
  { label: "Bookings",   icon: "📅", page: "admin-bookings" as Page },
];

export function AdminSidebar({ page, user, navigate, logout }: Props) {
  return (
    <aside className="w-60 flex-shrink-0 flex flex-col border-r" style={{ background: "#0d0820", borderColor: "rgba(124,58,237,0.15)", minHeight: "100vh" }}>
      {/* Logo */}
      <div className="p-6 border-b" style={{ borderColor: "rgba(124,58,237,0.15)" }}>
        <button onClick={() => navigate("home")} className="flex items-center gap-2">
          <span className="text-2xl">🎤</span>
          <span style={{ fontFamily: "'Noe Display', serif", fontWeight: 700, background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>KaraokeZone</span>
        </button>
        <p className="text-xs mt-1" style={{ color: "var(--muted-foreground)" }}>Admin Panel</p>
      </div>

      {/* Nav */}
      <nav className="flex-1 p-4 space-y-1">
        {NAV.map(({ label, icon, page: target }) => (
          <button
            key={label}
            onClick={() => navigate(target)}
            className={`w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-left ${page === target ? "text-white" : "text-slate-400 hover:text-white"}`}
            style={{ background: page === target ? "rgba(124,58,237,0.2)" : "transparent" }}
          >
            <span>{icon}</span>
            {label}
          </button>
        ))}
      </nav>

      {/* User + logout */}
      <div className="p-4 border-t" style={{ borderColor: "rgba(124,58,237,0.15)" }}>
        <div className="flex items-center gap-3 mb-3 px-2">
          <div className="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0" style={{ background: "var(--primary)" }}>
            {user?.name?.[0] ?? "A"}
          </div>
          <div className="overflow-hidden">
            <p className="text-sm font-medium text-white truncate">{user?.name ?? "Admin"}</p>
            <p className="text-xs truncate" style={{ color: "var(--muted-foreground)" }}>Administrator</p>
          </div>
        </div>
        <button onClick={() => navigate("home")} className="w-full flex items-center gap-2 px-4 py-2 rounded-xl text-sm text-slate-400 hover:text-white transition-colors">
          ← Back to Site
        </button>
        <button onClick={logout} className="w-full flex items-center gap-2 px-4 py-2 rounded-xl text-sm text-red-400 hover:text-red-300 transition-colors">
          ⬅ Logout
        </button>
      </div>
    </aside>
  );
}
