import { useState } from "react";
import { type Page, type AppUser } from "../App";
import { ROOMS, type Room } from "./data";
import { AdminSidebar } from "./AdminSidebar";

interface Props { navigate: (p: Page) => void; user: AppUser | null; logout: () => void; }

export function AdminRooms({ navigate, user, logout }: Props) {
  const [rooms, setRooms] = useState<Room[]>(ROOMS);
  const [modal, setModal] = useState<"create" | "edit" | null>(null);
  const [editing, setEditing] = useState<Room | null>(null);
  const [form, setForm] = useState({ name: "", type: "Standard", size: "Medium", capacity: 10, pricePerHour: 500, description: "", available: true });

  const openCreate = () => {
    setForm({ name: "", type: "Standard", size: "Medium", capacity: 10, pricePerHour: 500, description: "", available: true });
    setEditing(null);
    setModal("create");
  };

  const openEdit = (room: Room) => {
    setEditing(room);
    setForm({ name: room.name, type: room.type, size: room.size, capacity: room.capacity, pricePerHour: room.pricePerHour, description: room.description, available: room.available });
    setModal("edit");
  };

  const save = () => {
    if (!form.name.trim()) return;
    if (editing) {
      setRooms(rs => rs.map(r => r.id === editing.id ? { ...r, ...form, type: form.type as Room["type"], size: form.size as Room["size"] } : r));
    } else {
      const newRoom: Room = { id: Date.now(), ...form, type: form.type as Room["type"], size: form.size as Room["size"], amenities: ["Microphones", "HD TV", "Air Conditioning"], image: ROOMS[0].image };
      setRooms(rs => [...rs, newRoom]);
    }
    setModal(null);
  };

  const del = (id: number) => {
    if (window.confirm("Delete this room?")) setRooms(rs => rs.filter(r => r.id !== id));
  };

  const IS = { background: "var(--input-background)", borderColor: "var(--border)", color: "#fff" } as React.CSSProperties;
  const INPUT = "w-full rounded-lg px-3 py-2 text-sm border focus:outline-none";

  return (
    <div className="flex min-h-screen">
      <AdminSidebar page="admin-rooms" user={user} navigate={navigate} logout={logout} />

      <div className="flex-1 overflow-auto">
        <header className="sticky top-0 z-20 flex items-center justify-between px-6 py-4 border-b" style={{ background: "rgba(10,6,20,0.9)", backdropFilter: "blur(12px)", borderColor: "rgba(124,58,237,0.15)" }}>
          <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.25rem", fontWeight: 700, color: "#fff" }}>Manage Rooms</h1>
          <button onClick={openCreate} className="px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
            + Add Room
          </button>
        </header>

        <div className="p-6">
          <div className="rounded-2xl border overflow-hidden" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b text-xs uppercase" style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
                  {["Room", "Type / Size", "Capacity", "Price/hr", "Status", "Actions"].map(h => (
                    <th key={h} className="text-left px-5 py-3 font-medium">{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody className="divide-y" style={{ borderColor: "var(--border)" }}>
                {rooms.map(room => (
                  <tr key={room.id} className="hover:bg-white/[0.02] transition-colors">
                    <td className="px-5 py-4">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-lg overflow-hidden bg-slate-800 shrink-0">
                          <img src={room.image} alt={room.name} className="w-full h-full object-cover" />
                        </div>
                        <div>
                          <p className="text-white font-medium">{room.name}</p>
                          <p className="text-xs truncate max-w-[200px]" style={{ color: "var(--muted-foreground)" }}>{room.description.substring(0, 50)}…</p>
                        </div>
                      </div>
                    </td>
                    <td className="px-5 py-4">
                      <span className="text-violet-300">{room.type}</span>
                      <span style={{ color: "var(--muted-foreground)" }}> / </span>
                      <span className="text-slate-400">{room.size}</span>
                    </td>
                    <td className="px-5 py-4 text-slate-300">{room.capacity} pax</td>
                    <td className="px-5 py-4 font-semibold" style={{ color: "var(--accent)" }}>₱{room.pricePerHour.toLocaleString()}</td>
                    <td className="px-5 py-4">
                      {room.available
                        ? <span className="px-2.5 py-1 rounded-full text-xs bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 flex items-center gap-1 w-fit"><span className="w-1.5 h-1.5 rounded-full bg-emerald-400" />Available</span>
                        : <span className="px-2.5 py-1 rounded-full text-xs bg-red-500/15 text-red-400 border border-red-500/25 flex items-center gap-1 w-fit"><span className="w-1.5 h-1.5 rounded-full bg-red-400" />Unavailable</span>}
                    </td>
                    <td className="px-5 py-4">
                      <div className="flex gap-2">
                        <button onClick={() => openEdit(room)} className="px-3 py-1.5 rounded-lg text-xs border transition-colors hover:text-white" style={{ borderColor: "rgba(96,165,250,0.3)", color: "#93c5fd" }}>✏️ Edit</button>
                        <button onClick={() => del(room.id)} className="px-3 py-1.5 rounded-lg text-xs border transition-colors hover:text-white" style={{ borderColor: "rgba(239,68,68,0.3)", color: "#fca5a5" }}>🗑 Delete</button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {/* Modal */}
      {modal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: "rgba(0,0,0,0.7)" }} onClick={() => setModal(null)}>
          <div className="w-full max-w-lg rounded-2xl p-6 border" style={{ background: "#130d24", borderColor: "var(--border)" }} onClick={e => e.stopPropagation()}>
            <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.25rem", fontWeight: 700, color: "#fff", marginBottom: "1.25rem" }}>
              {modal === "create" ? "Add New Room" : "Edit Room"}
            </h2>
            <div className="space-y-4">
              <div>
                <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Room Name *</label>
                <input value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))} placeholder="e.g. The Purple Stage" className={INPUT} style={IS} />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Type</label>
                  <select value={form.type} onChange={e => setForm(f => ({ ...f, type: e.target.value }))} className={INPUT} style={IS}>
                    {["Standard", "Deluxe", "VIP", "Party"].map(t => <option key={t}>{t}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Size</label>
                  <select value={form.size} onChange={e => setForm(f => ({ ...f, size: e.target.value }))} className={INPUT} style={IS}>
                    {["Small", "Medium", "Large", "XL"].map(s => <option key={s}>{s}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Capacity (pax)</label>
                  <input type="number" value={form.capacity} onChange={e => setForm(f => ({ ...f, capacity: Number(e.target.value) }))} className={INPUT} style={IS} />
                </div>
                <div>
                  <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Price / Hour (₱)</label>
                  <input type="number" value={form.pricePerHour} onChange={e => setForm(f => ({ ...f, pricePerHour: Number(e.target.value) }))} className={INPUT} style={IS} />
                </div>
              </div>
              <div>
                <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Description</label>
                <textarea value={form.description} onChange={e => setForm(f => ({ ...f, description: e.target.value }))} rows={2} className={INPUT + " resize-none"} style={IS} />
              </div>
              <label className="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" checked={form.available} onChange={e => setForm(f => ({ ...f, available: e.target.checked }))} className="rounded" />
                <span className="text-sm text-white">Available for booking</span>
              </label>
            </div>
            <div className="flex gap-3 mt-6">
              <button onClick={save} className="flex-1 py-2.5 rounded-xl font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
                {modal === "create" ? "Create Room" : "Save Changes"}
              </button>
              <button onClick={() => setModal(null)} className="px-5 py-2.5 rounded-xl font-semibold border transition-colors hover:text-white" style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
                Cancel
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
