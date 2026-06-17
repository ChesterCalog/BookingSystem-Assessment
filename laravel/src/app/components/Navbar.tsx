import { useState } from "react";
import { type Page, type AppUser } from "../App";

interface Props {
  page: Page;
  user: AppUser | null;
  navigate: (p: Page) => void;
  logout: () => void;
}

export function Navbar({ page, user, navigate, logout }: Props) {
  const [open, setOpen] = useState(false);

  const link = (label: string, target: Page) => (
    <button
      onClick={() => { navigate(target); setOpen(false); }}
      className={`transition-colors duration-150 hover:text-white ${page === target ? "text-white font-semibold" : "text-slate-400"}`}
    >
      {label}
    </button>
  );

  return (
    <nav className="fixed top-0 inset-x-0 z-50 border-b" style={{ background: "rgba(10,6,20,0.85)", backdropFilter: "blur(16px)", borderColor: "rgba(124,58,237,0.15)" }}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6">
        <div className="flex items-center justify-between h-16">

          {/* Logo */}
          <button onClick={() => navigate("home")} className="flex items-center gap-2 group">
            <span className="text-2xl">🎤</span>
            <span style={{ fontFamily: "'Noe Display', serif", fontWeight: 700, background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", fontSize: "1.25rem" }}>
              KaraokeZone
            </span>
          </button>

          {/* Desktop nav */}
          <div className="hidden md:flex items-center gap-6 text-sm font-medium">
            {link("Home", "home")}
            {link("Rooms", "rooms")}
            <button onClick={() => navigate("guest-book")} className="text-slate-400 hover:text-white transition-colors">
              Book as Guest
            </button>

            {user ? (
              <>
                {user.role === "admin" ? (
                  <button onClick={() => navigate("admin")} className="text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-1">
                    ⚙️ Admin
                  </button>
                ) : (
                  <button onClick={() => navigate("book")} className="text-slate-400 hover:text-white transition-colors">
                    Book Now
                  </button>
                )}
                <button onClick={() => navigate("profile")} className="text-slate-400 hover:text-white transition-colors">
                  {user.name.split(" ")[0]}
                </button>
                <button onClick={logout} className="text-red-400 hover:text-red-300 transition-colors text-sm">
                  Logout
                </button>
              </>
            ) : (
              <>
                <button onClick={() => navigate("login")} className="text-slate-400 hover:text-white transition-colors">
                  Login
                </button>
                <button
                  onClick={() => navigate("register")}
                  className="px-4 py-2 rounded-lg text-white text-sm font-semibold transition-all hover:opacity-90"
                  style={{ background: "var(--primary)" }}
                >
                  Sign Up
                </button>
              </>
            )}
          </div>

          {/* Mobile hamburger */}
          <button onClick={() => setOpen(v => !v)} className="md:hidden text-slate-400 hover:text-white p-1">
            <svg width="22" height="22" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
              {open ? <path d="M6 18L18 6M6 6l12 12" /> : <path d="M4 6h16M4 12h16M4 18h16" />}
            </svg>
          </button>
        </div>
      </div>

      {/* Mobile menu */}
      {open && (
        <div className="md:hidden px-4 pb-4 space-y-3 text-sm border-t" style={{ borderColor: "rgba(124,58,237,0.15)" }}>
          {link("Home", "home")}
          {link("Rooms", "rooms")}
          <button onClick={() => { navigate("guest-book"); setOpen(false); }} className="block text-slate-400 hover:text-white">Book as Guest</button>
          {user ? (
            <>
              <button onClick={() => { navigate("profile"); setOpen(false); }} className="block text-slate-400 hover:text-white">{user.name}</button>
              <button onClick={() => { logout(); setOpen(false); }} className="block text-red-400">Logout</button>
            </>
          ) : (
            <>
              <button onClick={() => { navigate("login"); setOpen(false); }} className="block text-slate-400 hover:text-white">Login</button>
              <button onClick={() => { navigate("register"); setOpen(false); }} className="block text-violet-400 font-semibold">Sign Up</button>
            </>
          )}
        </div>
      )}
    </nav>
  );
}
