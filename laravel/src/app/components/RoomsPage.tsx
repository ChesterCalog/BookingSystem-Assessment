import { useState } from "react";
import { type Page } from "../App";
import { ROOMS, type Room } from "./data";

interface Props {
  navigate: (p: Page, id?: number) => void;
}

export function RoomsPage({ navigate }: Props) {
  const [type, setType] = useState("");
  const [minCap, setMinCap] = useState("");
  const [maxPrice, setMaxPrice] = useState("");

  const filtered = ROOMS.filter(r => {
    if (type && r.type !== type) return false;
    if (minCap && r.capacity < Number(minCap)) return false;
    if (maxPrice && r.pricePerHour > Number(maxPrice)) return false;
    return true;
  });

  const typeColor: Record<string, string> = {
    Standard: "bg-slate-500/20 text-slate-300",
    Deluxe:   "bg-blue-500/15 text-blue-300",
    VIP:      "bg-violet-500/20 text-violet-300",
    Party:    "bg-amber-500/15 text-amber-300",
  };

  return (
    <div className="pt-20 pb-16 min-h-screen">
      {/* Page header */}
      <div className="py-14 text-center" style={{ background: "linear-gradient(to bottom, rgba(124,58,237,0.08), transparent)" }}>
        <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2.2rem,5vw,3.5rem)", fontWeight: 700, color: "#fff" }}>
          Browse Our <span style={{ background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>Rooms</span>
        </h1>
        <p className="mt-3 max-w-lg mx-auto" style={{ color: "var(--muted-foreground)" }}>
          Filter by type, capacity, or price to find your perfect karaoke room.
        </p>
      </div>

      <div className="max-w-7xl mx-auto px-4">
        {/* Filter bar */}
        <div className="flex flex-wrap gap-3 mb-8 p-5 rounded-2xl border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
          <div className="flex-1 min-w-36">
            <label className="block text-xs mb-1.5" style={{ color: "var(--muted-foreground)" }}>Room Type</label>
            <select value={type} onChange={e => setType(e.target.value)}
              className="w-full rounded-lg px-3 py-2 text-sm text-white border focus:outline-none"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }}>
              <option value="">All Types</option>
              {["Standard", "Deluxe", "VIP", "Party"].map(t => <option key={t}>{t}</option>)}
            </select>
          </div>
          <div className="flex-1 min-w-36">
            <label className="block text-xs mb-1.5" style={{ color: "var(--muted-foreground)" }}>Min. Capacity</label>
            <input type="number" placeholder="e.g. 8" value={minCap} onChange={e => setMinCap(e.target.value)} min="1"
              className="w-full rounded-lg px-3 py-2 text-sm text-white border focus:outline-none placeholder:text-slate-600"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }} />
          </div>
          <div className="flex-1 min-w-36">
            <label className="block text-xs mb-1.5" style={{ color: "var(--muted-foreground)" }}>Max Price/hr (₱)</label>
            <input type="number" placeholder="e.g. 1000" value={maxPrice} onChange={e => setMaxPrice(e.target.value)} min="0"
              className="w-full rounded-lg px-3 py-2 text-sm text-white border focus:outline-none placeholder:text-slate-600"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }} />
          </div>
          <div className="flex items-end">
            <button onClick={() => { setType(""); setMinCap(""); setMaxPrice(""); }}
              className="px-4 py-2 rounded-lg text-sm border transition-colors hover:text-white"
              style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
              Clear
            </button>
          </div>
        </div>

        <p className="text-sm mb-5" style={{ color: "var(--muted-foreground)" }}>
          Showing <span className="text-white font-semibold">{filtered.length}</span> room{filtered.length !== 1 ? "s" : ""}
          {(type || minCap || maxPrice) && " (filtered)"}
        </p>

        {filtered.length === 0 ? (
          <div className="text-center py-20 rounded-2xl border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <span className="text-5xl opacity-20">🎤</span>
            <p className="mt-4" style={{ color: "var(--muted-foreground)" }}>No rooms match your filters.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filtered.map(room => <RoomCard key={room.id} room={room} navigate={navigate} typeColor={typeColor} />)}
          </div>
        )}
      </div>
    </div>
  );
}

function RoomCard({ room, navigate, typeColor }: { room: Room; navigate: (p: Page, id?: number) => void; typeColor: Record<string, string> }) {
  return (
    <div className="rounded-2xl overflow-hidden border group cursor-pointer transition-all hover:-translate-y-1"
      style={{ background: "var(--card)", borderColor: "var(--border)" }}
      onClick={() => navigate("room-detail", room.id)}>
      <div className="relative h-52 overflow-hidden bg-slate-900">
        <img src={room.image} alt={room.name} className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
        <div className="absolute inset-0" style={{ background: "linear-gradient(to top, rgba(10,6,20,0.5) 0%, transparent 60%)" }} />
        <div className="absolute top-3 left-3 flex gap-2">
          <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${typeColor[room.type]}`}>{room.type}</span>
          <span className="px-2.5 py-1 rounded-full text-xs" style={{ background: "rgba(10,6,20,0.7)", color: "#e2e8f0" }}>{room.size}</span>
        </div>
        <span className="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs" style={{ background: "rgba(10,6,20,0.7)", color: "#e2e8f0" }}>
          👥 {room.capacity} pax
        </span>
      </div>
      <div className="p-5 flex flex-col">
        <h3 className="font-semibold text-white text-lg mb-1">{room.name}</h3>
        <p className="text-sm mb-4 line-clamp-2" style={{ color: "var(--muted-foreground)" }}>{room.description}</p>
        <div className="flex flex-wrap gap-1.5 mb-4">
          {room.amenities.slice(0, 4).map(a => (
            <span key={a} className="text-xs px-2 py-0.5 rounded-full border" style={{ background: "rgba(124,58,237,0.08)", borderColor: "rgba(124,58,237,0.2)", color: "#c4b5fd" }}>{a}</span>
          ))}
          {room.amenities.length > 4 && <span className="text-xs" style={{ color: "var(--muted-foreground)" }}>+{room.amenities.length - 4} more</span>}
        </div>
        <div className="flex items-center justify-between pt-3 border-t mt-auto" style={{ borderColor: "var(--border)" }}>
          <div>
            <span style={{ fontFamily: "'Noe Display', serif", fontSize: "1.5rem", fontWeight: 700, color: "var(--accent)" }}>₱{room.pricePerHour.toLocaleString()}</span>
            <span className="text-sm ml-1" style={{ color: "var(--muted-foreground)" }}>/hr</span>
          </div>
          <button className="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
            Book Now
          </button>
        </div>
      </div>
    </div>
  );
}
