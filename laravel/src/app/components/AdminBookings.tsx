import { useState } from "react";
import { type Page, type AppUser } from "../App";
import { SAMPLE_BOOKINGS, statusColor, type Booking } from "./data";
import { AdminSidebar } from "./AdminSidebar";

interface Props { navigate: (p: Page) => void; user: AppUser | null; logout: () => void; }

export function AdminBookings({ navigate, user, logout }: Props) {
  const [bookings, setBookings] = useState<Booking[]>(SAMPLE_BOOKINGS);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("");
  const [typeFilter, setTypeFilter] = useState("");
  const [detail, setDetail] = useState<Booking | null>(null);

  const filtered = bookings.filter(b => {
    const s = search.toLowerCase();
    const matchSearch = !s || b.name.toLowerCase().includes(s) || b.email.toLowerCase().includes(s) || b.refNumber.toLowerCase().includes(s);
    const matchStatus = !statusFilter || b.status === statusFilter;
    const matchType   = !typeFilter   || b.type === typeFilter;
    return matchSearch && matchStatus && matchType;
  });

  const updateStatus = (id: number, status: Booking["status"]) => {
    setBookings(bs => bs.map(b => b.id === id ? { ...b, status } : b));
    if (detail?.id === id) setDetail(d => d ? { ...d, status } : null);
  };

  return (
    <div className="flex min-h-screen">
      <AdminSidebar page="admin-bookings" user={user} navigate={navigate} logout={logout} />

      <div className="flex-1 overflow-auto">
        <header className="sticky top-0 z-20 flex items-center justify-between px-6 py-4 border-b" style={{ background: "rgba(10,6,20,0.9)", backdropFilter: "blur(12px)", borderColor: "rgba(124,58,237,0.15)" }}>
          <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.25rem", fontWeight: 700, color: "#fff" }}>Manage Bookings</h1>
          <span className="text-sm px-3 py-1 rounded-full" style={{ background: "rgba(124,58,237,0.15)", color: "#c4b5fd" }}>{filtered.length} booking{filtered.length !== 1 ? "s" : ""}</span>
        </header>

        <div className="p-6 space-y-5">
          {/* Filters */}
          <div className="flex flex-wrap gap-3 p-4 rounded-2xl border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search name, email, or ref…"
              className="flex-1 min-w-48 rounded-lg px-3 py-2 text-white text-sm border focus:outline-none placeholder:text-slate-600"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }} />
            <select value={statusFilter} onChange={e => setStatusFilter(e.target.value)}
              className="rounded-lg px-3 py-2 text-white text-sm border focus:outline-none"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }}>
              <option value="">All Statuses</option>
              {["Pending", "Approved", "Rejected", "Completed", "Cancelled"].map(s => <option key={s}>{s}</option>)}
            </select>
            <select value={typeFilter} onChange={e => setTypeFilter(e.target.value)}
              className="rounded-lg px-3 py-2 text-white text-sm border focus:outline-none"
              style={{ background: "var(--input-background)", borderColor: "var(--border)" }}>
              <option value="">All Types</option>
              <option value="member">Member</option>
              <option value="guest">Guest</option>
            </select>
            {(search || statusFilter || typeFilter) && (
              <button onClick={() => { setSearch(""); setStatusFilter(""); setTypeFilter(""); }}
                className="px-3 py-2 rounded-lg text-sm border transition-colors hover:text-white"
                style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
                Clear
              </button>
            )}
          </div>

          {/* Table */}
          <div className="rounded-2xl border overflow-hidden" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b text-xs uppercase" style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
                    {["Ref #", "Type", "Name / Email", "Room", "Date & Time", "Cost", "Status", "Actions"].map(h => (
                      <th key={h} className="text-left px-5 py-3 font-medium whitespace-nowrap">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody className="divide-y" style={{ borderColor: "var(--border)" }}>
                  {filtered.length === 0 && (
                    <tr><td colSpan={8} className="px-5 py-10 text-center" style={{ color: "var(--muted-foreground)" }}>No bookings found.</td></tr>
                  )}
                  {filtered.map(b => (
                    <tr key={b.id} className="hover:bg-white/[0.02] transition-colors">
                      <td className="px-5 py-3 font-mono text-xs" style={{ color: "var(--accent)" }}>{b.refNumber}</td>
                      <td className="px-5 py-3">
                        <span className={`px-2 py-0.5 rounded-full text-xs ${b.type === "member" ? "bg-blue-500/15 text-blue-300" : "bg-slate-500/15 text-slate-300"}`}>
                          {b.type === "member" ? "Member" : "Guest"}
                        </span>
                      </td>
                      <td className="px-5 py-3">
                        <p className="text-white">{b.name}</p>
                        <p className="text-xs" style={{ color: "var(--muted-foreground)" }}>{b.email}</p>
                      </td>
                      <td className="px-5 py-3 text-slate-300 whitespace-nowrap">{b.room}</td>
                      <td className="px-5 py-3">
                        <p className="text-white whitespace-nowrap">{b.date}</p>
                        <p className="text-xs" style={{ color: "var(--muted-foreground)" }}>{b.startTime}–{b.endTime}</p>
                      </td>
                      <td className="px-5 py-3 text-white font-medium whitespace-nowrap">₱{b.totalCost.toLocaleString()}</td>
                      <td className="px-5 py-3">
                        <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap ${statusColor(b.status)}`}>{b.status}</span>
                      </td>
                      <td className="px-5 py-3">
                        <div className="flex gap-1">
                          <button onClick={() => setDetail(b)} className="px-2.5 py-1.5 rounded-lg text-xs border transition-colors hover:text-white" style={{ borderColor: "rgba(255,255,255,0.1)", color: "#94a3b8" }}>👁</button>
                          {b.status === "Pending" && (
                            <>
                              <button onClick={() => updateStatus(b.id, "Approved")} className="px-2.5 py-1.5 rounded-lg text-xs border transition-colors" style={{ borderColor: "rgba(52,211,153,0.3)", color: "#6ee7b7" }}>✓</button>
                              <button onClick={() => updateStatus(b.id, "Rejected")} className="px-2.5 py-1.5 rounded-lg text-xs border transition-colors" style={{ borderColor: "rgba(239,68,68,0.3)", color: "#fca5a5" }}>✗</button>
                            </>
                          )}
                          {b.status === "Approved" && (
                            <button onClick={() => updateStatus(b.id, "Completed")} className="px-2.5 py-1.5 rounded-lg text-xs border transition-colors" style={{ borderColor: "rgba(96,165,250,0.3)", color: "#93c5fd" }}>🏁</button>
                          )}
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {/* Detail modal */}
      {detail && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4" style={{ background: "rgba(0,0,0,0.75)" }} onClick={() => setDetail(null)}>
          <div className="w-full max-w-md rounded-2xl p-6 border" style={{ background: "#130d24", borderColor: "var(--border)" }} onClick={e => e.stopPropagation()}>
            <div className="flex items-start justify-between mb-5">
              <div>
                <p className="text-xs mb-0.5" style={{ color: "var(--muted-foreground)" }}>Reference Number</p>
                <p style={{ fontFamily: "monospace", fontSize: "1.25rem", fontWeight: 700, color: "var(--accent)" }}>{detail.refNumber}</p>
              </div>
              <span className={`px-3 py-1 rounded-full text-sm font-semibold ${statusColor(detail.status)}`}>{detail.status}</span>
            </div>

            <div className="grid grid-cols-2 gap-4 text-sm mb-5">
              {[
                ["Name", detail.name], ["Email", detail.email], ["Phone", detail.phone],
                ["Type", detail.type === "member" ? "Member" : "Guest"],
                ["Room", detail.room], ["Guests", `${detail.guests} pax`],
                ["Date", detail.date], ["Time", `${detail.startTime}–${detail.endTime}`],
              ].map(([label, value]) => (
                <div key={label as string}>
                  <p className="text-xs mb-0.5" style={{ color: "var(--muted-foreground)" }}>{label}</p>
                  <p className="text-white">{value}</p>
                </div>
              ))}
              <div className="col-span-2">
                <p className="text-xs mb-0.5" style={{ color: "var(--muted-foreground)" }}>Total Cost</p>
                <p style={{ fontFamily: "'Noe Display', serif", fontSize: "1.5rem", fontWeight: 700, color: "var(--accent)" }}>₱{detail.totalCost.toLocaleString()}</p>
              </div>
            </div>

            {detail.status === "Pending" && (
              <div className="flex gap-3">
                <button onClick={() => updateStatus(detail.id, "Approved")} className="flex-1 py-2.5 rounded-xl font-semibold text-white transition-all hover:opacity-90" style={{ background: "#059669" }}>✓ Approve</button>
                <button onClick={() => updateStatus(detail.id, "Rejected")} className="flex-1 py-2.5 rounded-xl font-semibold text-white transition-all hover:opacity-90" style={{ background: "#dc2626" }}>✗ Reject</button>
              </div>
            )}
            {detail.status === "Approved" && (
              <button onClick={() => updateStatus(detail.id, "Completed")} className="w-full py-2.5 rounded-xl font-semibold text-white transition-all hover:opacity-90" style={{ background: "#2563eb" }}>🏁 Mark Completed</button>
            )}
            <button onClick={() => setDetail(null)} className="w-full mt-3 py-2.5 rounded-xl border text-sm transition-colors hover:text-white" style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>Close</button>
          </div>
        </div>
      )}
    </div>
  );
}
