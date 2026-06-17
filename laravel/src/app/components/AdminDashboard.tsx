import { type Page, type AppUser } from "../App";
import { SAMPLE_BOOKINGS, ROOMS, statusColor } from "./data";
import { AdminSidebar } from "./AdminSidebar";

interface Props { navigate: (p: Page) => void; user: AppUser | null; logout: () => void; }

export function AdminDashboard({ navigate, user, logout }: Props) {
  const totalBookings  = SAMPLE_BOOKINGS.length;
  const pending        = SAMPLE_BOOKINGS.filter(b => b.status === "Pending").length;
  const totalRevenue   = SAMPLE_BOOKINGS.filter(b => b.status === "Completed").reduce((s, b) => s + b.totalCost, 0);
  const totalRooms     = ROOMS.length;
  const totalUsers     = 4;

  const STATS = [
    { label: "Total Bookings", value: totalBookings, icon: "📅", color: "#a78bfa" },
    { label: "Pending",        value: pending,       icon: "⏳", color: "#fbbf24" },
    { label: "Total Users",    value: totalUsers,    icon: "👥", color: "#60a5fa" },
    { label: "Total Rooms",    value: totalRooms,    icon: "🚪", color: "#34d399" },
    { label: "Revenue",        value: `₱${totalRevenue.toLocaleString()}`, icon: "💰", color: "#f59e0b" },
  ];

  // Simple bar chart — last 6 months revenue (mock data)
  const monthlyData = [
    { month: "Feb", rev: 28000 },
    { month: "Mar", rev: 41000 },
    { month: "Apr", rev: 35000 },
    { month: "May", rev: 52000 },
    { month: "Jun", rev: 48000 },
    { month: "Jul", rev: totalRevenue || 21600 },
  ];
  const maxRev = Math.max(...monthlyData.map(d => d.rev));

  return (
    <div className="flex min-h-screen">
      <AdminSidebar page="admin" user={user} navigate={navigate} logout={logout} />

      <div className="flex-1 overflow-auto">
        {/* Top bar */}
        <header className="sticky top-0 z-20 flex items-center justify-between px-6 py-4 border-b" style={{ background: "rgba(10,6,20,0.9)", backdropFilter: "blur(12px)", borderColor: "rgba(124,58,237,0.15)" }}>
          <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.25rem", fontWeight: 700, color: "#fff" }}>Dashboard</h1>
          <p className="text-sm" style={{ color: "var(--muted-foreground)" }}>{new Date().toLocaleDateString("en-PH", { weekday: "long", year: "numeric", month: "long", day: "numeric" })}</p>
        </header>

        <div className="p-6 space-y-6">
          {/* Stats */}
          <div className="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {STATS.map(({ label, value, icon, color }) => (
              <div key={label} className="rounded-2xl p-5 border flex items-center gap-4" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
                <div className="w-11 h-11 rounded-xl flex items-center justify-center text-xl shrink-0" style={{ background: `${color}18` }}>
                  {icon}
                </div>
                <div>
                  <p className="text-xs" style={{ color: "var(--muted-foreground)" }}>{label}</p>
                  <p style={{ fontFamily: "'Noe Display', serif", fontSize: "1.4rem", fontWeight: 700, color: "#fff" }}>{value}</p>
                </div>
              </div>
            ))}
          </div>

          {/* Chart + Quick Actions */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Revenue chart */}
            <div className="lg:col-span-2 rounded-2xl p-6 border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
              <h2 className="font-semibold text-white mb-6">Monthly Revenue (Last 6 Months)</h2>
              <div className="flex items-end gap-3 h-40">
                {monthlyData.map(({ month, rev }) => (
                  <div key={month} className="flex-1 flex flex-col items-center gap-1">
                    <span className="text-xs" style={{ color: "var(--muted-foreground)" }}>₱{(rev/1000).toFixed(0)}k</span>
                    <div className="w-full rounded-t-lg transition-all" style={{ height: `${(rev / maxRev) * 120}px`, background: "linear-gradient(to top, #7c3aed, #a78bfa)" }} />
                    <span className="text-xs" style={{ color: "var(--muted-foreground)" }}>{month}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Quick actions */}
            <div className="rounded-2xl p-6 border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
              <h2 className="font-semibold text-white mb-4">Quick Actions</h2>
              <div className="space-y-3">
                {[
                  { label: "Pending Approvals", sub: `${pending} awaiting`, icon: "⏳", page: "admin-bookings" as Page, color: "#fbbf24" },
                  { label: "Add New Room",       sub: "Expand inventory",    icon: "➕", page: "admin-rooms" as Page,    color: "#a78bfa" },
                  { label: "View All Bookings",  sub: "Search & filter",     icon: "📋", page: "admin-bookings" as Page, color: "#60a5fa" },
                ].map(({ label, sub, icon, page, color }) => (
                  <button key={label} onClick={() => navigate(page)}
                    className="w-full flex items-center gap-3 p-3 rounded-xl border transition-all hover:opacity-80 text-left"
                    style={{ background: `${color}10`, borderColor: `${color}20` }}>
                    <span className="text-xl">{icon}</span>
                    <div>
                      <p className="text-white text-sm font-medium">{label}</p>
                      <p className="text-xs" style={{ color }}>{sub}</p>
                    </div>
                    <span className="ml-auto text-slate-600">→</span>
                  </button>
                ))}
              </div>
            </div>
          </div>

          {/* Recent bookings */}
          <div className="rounded-2xl border overflow-hidden" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <div className="flex items-center justify-between p-6 pb-4">
              <h2 className="font-semibold text-white">Recent Bookings</h2>
              <button onClick={() => navigate("admin-bookings")} className="text-sm text-violet-400 hover:text-white">View all →</button>
            </div>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-y text-xs uppercase" style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}>
                    {["Reference", "Type", "Name", "Room", "Date", "Cost", "Status"].map(h => (
                      <th key={h} className="text-left px-6 py-3 font-medium">{h}</th>
                    ))}
                  </tr>
                </thead>
                <tbody className="divide-y" style={{ borderColor: "var(--border)" }}>
                  {SAMPLE_BOOKINGS.slice(0, 5).map(b => (
                    <tr key={b.id} className="hover:bg-white/[0.02] transition-colors">
                      <td className="px-6 py-3 font-mono text-xs" style={{ color: "var(--accent)" }}>{b.refNumber}</td>
                      <td className="px-6 py-3">
                        <span className={`px-2 py-0.5 rounded-full text-xs ${b.type === "member" ? "bg-blue-500/15 text-blue-300" : "bg-slate-500/15 text-slate-300"}`}>
                          {b.type === "member" ? "Member" : "Guest"}
                        </span>
                      </td>
                      <td className="px-6 py-3 text-white">{b.name}</td>
                      <td className="px-6 py-3 text-slate-300">{b.room}</td>
                      <td className="px-6 py-3 text-slate-400">{b.date}</td>
                      <td className="px-6 py-3 text-white font-medium">₱{b.totalCost.toLocaleString()}</td>
                      <td className="px-6 py-3">
                        <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusColor(b.status)}`}>{b.status}</span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
