/**
 * RoomDetailPage
 *
 * Shows room info on the left.
 * Right panel: booking type selector (Guest / Registered User) that
 * navigates directly into the unified BookingPage — no duplicate
 * time inputs, no separate "Reserve This Room" widget.
 */
import { useState, useCallback } from "react";
import { type Page, type AppUser } from "../App";
import { ROOMS } from "./data";

// ── Module-scope sub-components (prevent focus loss) ─────────────

/** Segmented booking-type selector — mirrors the one in BookingPage */
function BookingTypePanel({
  mode,
  user,
  room,
  navigate,
  onModeChange,
}: {
  mode: "guest" | "user";
  user: AppUser | null;
  room: ReturnType<typeof ROOMS[0]["id"] extends number ? typeof ROOMS[number] : never>;
  navigate: (p: Page, id?: number) => void;
  onModeChange: (m: "guest" | "user") => void;
}) {
  const handleBook = () => {
    if (mode === "user" && !user) {
      navigate("login");
      return;
    }
    // Both guest and user now flow into the same BookingPage
    const target: Page = mode === "user" ? "book" : "guest-book";
    navigate(target, room.id);
  };

  return (
    <div className="space-y-4">
      {/* Price */}
      <div className="flex items-end justify-between">
        <div>
          <span
            style={{
              fontFamily: "'Noe Display', serif",
              fontSize: "2rem",
              fontWeight: 700,
              color: "var(--accent)",
            }}
          >
            ₱{room.pricePerHour.toLocaleString()}
          </span>
          <span className="text-sm ml-1" style={{ color: "var(--muted-foreground)" }}>/ hour</span>
        </div>
        <div className="flex items-center gap-1.5 text-sm">
          <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
          <span style={{ color: "#34d399" }}>Available</span>
        </div>
      </div>

      {/* Divider */}
      <hr style={{ borderColor: "var(--border)" }} />

      {/* Booking type toggle */}
      <div>
        <p className="text-xs font-medium mb-2" style={{ color: "var(--muted-foreground)" }}>
          How would you like to book?
        </p>
        <div
          className="flex rounded-xl p-1 gap-1"
          style={{ background: "rgba(255,255,255,0.05)", border: "1px solid var(--border)" }}
        >
          {(["guest", "user"] as const).map((m) => {
            const active = mode === m;
            return (
              <button
                key={m}
                type="button"
                onClick={() => onModeChange(m)}
                className="flex-1 flex flex-col items-center gap-0.5 px-3 py-2.5 rounded-lg transition-all"
                style={{
                  background: active ? "var(--primary)" : "transparent",
                  boxShadow: active ? "0 4px 14px rgba(124,58,237,0.3)" : "none",
                }}
              >
                <span className="text-base">{m === "guest" ? "👤" : "🎫"}</span>
                <span
                  className="text-xs font-semibold leading-tight text-center"
                  style={{ color: active ? "#fff" : "var(--muted-foreground)" }}
                >
                  {m === "guest" ? "Guest" : "Member"}
                </span>
              </button>
            );
          })}
        </div>
      </div>

      {/* Context info under toggle */}
      {mode === "guest" && (
        <p className="text-xs px-1" style={{ color: "var(--muted-foreground)" }}>
          No account required. Provide your name and contact number to continue.
        </p>
      )}

      {mode === "user" && user && (
        <div
          className="flex items-center gap-3 p-3 rounded-xl border"
          style={{ background: "rgba(52,211,153,0.06)", borderColor: "rgba(52,211,153,0.2)" }}
        >
          <div
            className="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
            style={{ background: "var(--primary)" }}
          >
            {user.name[0]}
          </div>
          <div className="min-w-0">
            <p className="text-white text-sm font-medium truncate">{user.name}</p>
            <p className="text-xs truncate" style={{ color: "var(--muted-foreground)" }}>{user.email}</p>
          </div>
          <span
            className="text-xs px-2 py-0.5 rounded-full shrink-0 flex items-center gap-1"
            style={{ background: "rgba(52,211,153,0.12)", color: "#34d399", border: "1px solid rgba(52,211,153,0.2)" }}
          >
            <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
            Signed in
          </span>
        </div>
      )}

      {mode === "user" && !user && (
        <div
          className="p-3 rounded-xl border text-center"
          style={{ background: "rgba(124,58,237,0.06)", borderColor: "rgba(124,58,237,0.2)" }}
        >
          <p className="text-xs mb-2" style={{ color: "var(--muted-foreground)" }}>
            Sign in to book as a registered member.
          </p>
          <div className="flex gap-2 justify-center">
            <button
              type="button"
              onClick={() => navigate("login")}
              className="px-4 py-1.5 rounded-lg text-xs font-semibold text-white transition-all hover:opacity-90"
              style={{ background: "var(--primary)" }}
            >
              Sign In
            </button>
            <button
              type="button"
              onClick={() => navigate("register")}
              className="px-4 py-1.5 rounded-lg text-xs font-semibold border transition-all hover:text-white"
              style={{ borderColor: "rgba(124,58,237,0.4)", color: "#c4b5fd" }}
            >
              Sign Up
            </button>
          </div>
        </div>
      )}

      {/* Main CTA */}
      <button
        type="button"
        onClick={handleBook}
        className="w-full py-3.5 rounded-xl font-bold text-white transition-all hover:opacity-90"
        style={{
          background: "var(--primary)",
          boxShadow: "0 6px 20px rgba(124,58,237,0.35)",
        }}
      >
        🎤 Book This Room
      </button>

      {/* Quick facts */}
      <div className="grid grid-cols-2 gap-2 pt-1">
        {[
          { icon: "👥", val: `Up to ${room.capacity} pax` },
          { icon: "🏷️", val: room.type },
          { icon: "📐", val: room.size },
          { icon: "✅", val: "Instant booking" },
        ].map(({ icon, val }) => (
          <div
            key={val}
            className="flex items-center gap-2 px-3 py-2 rounded-lg text-xs"
            style={{ background: "rgba(255,255,255,0.03)", color: "var(--muted-foreground)" }}
          >
            <span>{icon}</span>
            <span>{val}</span>
          </div>
        ))}
      </div>
    </div>
  );
}

// ── Main component ────────────────────────────────────────────────

interface Props {
  roomId: number;
  navigate: (p: Page, id?: number) => void;
  user: AppUser | null;
}

const TYPE_GRAD: Record<string, string> = {
  Standard: "from-slate-700 to-slate-900",
  Deluxe:   "from-blue-900 to-slate-900",
  VIP:      "from-violet-900 to-slate-900",
  Party:    "from-amber-900 to-slate-900",
};

export function RoomDetailPage({ roomId, navigate, user }: Props) {
  const room = ROOMS.find((r) => r.id === roomId) ?? ROOMS[0];

  // Default to "user" if already signed in, otherwise "guest"
  const [mode, setMode] = useState<"guest" | "user">(user ? "user" : "guest");

  const handleModeChange = useCallback((m: "guest" | "user") => setMode(m), []);

  return (
    <div className="pt-20 pb-16 min-h-screen">
      <div className="max-w-6xl mx-auto px-4">

        {/* Breadcrumb */}
        <nav
          className="flex items-center gap-2 text-sm py-6"
          style={{ color: "var(--muted-foreground)" }}
        >
          <button
            type="button"
            onClick={() => navigate("home")}
            className="hover:text-white transition-colors"
          >
            Home
          </button>
          <span>/</span>
          <button
            type="button"
            onClick={() => navigate("rooms")}
            className="hover:text-white transition-colors"
          >
            Rooms
          </button>
          <span>/</span>
          <span className="text-white">{room.name}</span>
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-8">

          {/* ── Left: room details ─────────────────────────── */}
          <div className="lg:col-span-3 space-y-6">

            {/* Hero image */}
            <div
              className={`rounded-2xl overflow-hidden h-72 bg-gradient-to-br ${TYPE_GRAD[room.type] ?? "from-slate-800 to-slate-900"}`}
            >
              <img
                src={room.image}
                alt={room.name}
                className="w-full h-full object-cover"
              />
            </div>

            {/* Info card */}
            <div
              className="rounded-2xl p-6 border"
              style={{ background: "var(--card)", borderColor: "var(--border)" }}
            >
              <div className="flex flex-wrap items-start justify-between gap-4 mb-4">
                <div>
                  <h1
                    style={{
                      fontFamily: "'Noe Display', serif",
                      fontSize: "1.8rem",
                      fontWeight: 700,
                      color: "#fff",
                    }}
                  >
                    {room.name}
                  </h1>
                  <div className="flex gap-2 mt-2">
                    <span
                      className="text-xs px-3 py-1 rounded-full font-semibold"
                      style={{ background: "rgba(124,58,237,0.2)", color: "#c4b5fd" }}
                    >
                      {room.type}
                    </span>
                    <span
                      className="text-xs px-3 py-1 rounded-full"
                      style={{ background: "rgba(255,255,255,0.06)", color: "#94a3b8" }}
                    >
                      {room.size}
                    </span>
                  </div>
                </div>
                <div className="text-right">
                  <span
                    style={{
                      fontFamily: "'Noe Display', serif",
                      fontSize: "2rem",
                      fontWeight: 700,
                      color: "var(--accent)",
                    }}
                  >
                    ₱{room.pricePerHour.toLocaleString()}
                  </span>
                  <p className="text-sm" style={{ color: "var(--muted-foreground)" }}>per hour</p>
                </div>
              </div>

              <p className="leading-relaxed" style={{ color: "var(--muted-foreground)" }}>
                {room.description}
              </p>

              {/* Stat tiles */}
              <div className="grid grid-cols-3 gap-3 mt-6">
                {[
                  { icon: "👥", val: `${room.capacity}`, label: "Max Guests" },
                  { icon: "🎤", val: room.type,          label: "Room Type" },
                  { icon: "📐", val: room.size,          label: "Room Size" },
                ].map(({ icon, val, label }) => (
                  <div
                    key={label}
                    className="rounded-xl p-3 text-center"
                    style={{ background: "rgba(124,58,237,0.1)" }}
                  >
                    <div className="text-xl mb-1">{icon}</div>
                    <p className="text-white font-semibold text-sm">{val}</p>
                    <p className="text-xs mt-0.5" style={{ color: "var(--muted-foreground)" }}>
                      {label}
                    </p>
                  </div>
                ))}
              </div>
            </div>

            {/* Amenities */}
            <div
              className="rounded-2xl p-6 border"
              style={{ background: "var(--card)", borderColor: "var(--border)" }}
            >
              <h2 className="font-semibold text-white mb-4">Room Amenities</h2>
              <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                {room.amenities.map((a) => (
                  <div
                    key={a}
                    className="flex items-center gap-2 text-sm"
                    style={{ color: "var(--muted-foreground)" }}
                  >
                    <span className="text-emerald-400 text-xs">✓</span>
                    {a}
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* ── Right: booking panel ───────────────────────── */}
          <div className="lg:col-span-2">
            <div
              className="rounded-2xl p-6 border sticky top-24"
              style={{ background: "var(--card)", borderColor: "var(--border)" }}
            >
              <BookingTypePanel
                mode={mode}
                user={user}
                room={room}
                navigate={navigate}
                onModeChange={handleModeChange}
              />
            </div>
          </div>

        </div>
      </div>
    </div>
  );
}
