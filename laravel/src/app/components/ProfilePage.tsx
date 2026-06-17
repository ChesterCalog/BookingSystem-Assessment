import { type Page, type AppUser } from "../App";
import { SAMPLE_BOOKINGS, statusColor } from "./data";

interface Props { navigate: (p: Page) => void; user: AppUser | null; }

export function ProfilePage({ navigate, user }: Props) {
  if (!user) {
    return (
      <div className="pt-32 min-h-screen flex items-center justify-center text-center px-4">
        <div>
          <p className="text-white text-xl mb-4">Please log in to view your profile.</p>
          <button onClick={() => navigate("login")} className="px-6 py-3 rounded-xl font-semibold text-white" style={{ background: "var(--primary)" }}>Sign In</button>
        </div>
      </div>
    );
  }

  const myBookings = SAMPLE_BOOKINGS.filter(b => b.name === user.name || b.type === "member").slice(0, 5);

  return (
    <div className="pt-20 pb-16 min-h-screen">
      <div className="max-w-5xl mx-auto px-4 py-10">
        <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "2rem", fontWeight: 700, color: "#fff", marginBottom: "2rem" }}>My Profile</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Profile card */}
          <div className="rounded-2xl p-6 border h-fit" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
            <div className="flex items-center gap-4 mb-6">
              <div className="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white" style={{ background: "var(--primary)" }}>
                {user.name[0]}
              </div>
              <div>
                <p className="text-white font-semibold text-lg">{user.name}</p>
                <p className="text-sm" style={{ color: "var(--muted-foreground)" }}>{user.email}</p>
              </div>
            </div>

            <div className="space-y-4">
              {[
                { label: "Full Name", value: user.name },
                { label: "Email", value: user.email },
                { label: "Phone", value: "+63 912 111 1111" },
                { label: "Member Since", value: "January 2025" },
              ].map(({ label, value }) => (
                <div key={label}>
                  <label className="block text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>{label}</label>
                  <p className="text-white text-sm font-medium">{value}</p>
                </div>
              ))}
            </div>

            <button className="mt-6 w-full py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
              Edit Profile
            </button>
          </div>

          {/* Booking history */}
          <div className="lg:col-span-2">
            <div className="flex items-center justify-between mb-4">
              <h2 className="font-semibold text-white text-lg">Booking History</h2>
              <button onClick={() => navigate("book")} className="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
                + New Booking
              </button>
            </div>

            {myBookings.length === 0 ? (
              <div className="rounded-2xl p-12 text-center border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
                <p className="text-4xl opacity-20 mb-3">📋</p>
                <p style={{ color: "var(--muted-foreground)" }}>No bookings yet.</p>
                <button onClick={() => navigate("book")} className="text-violet-400 text-sm mt-2 hover:text-white">Book a room now →</button>
              </div>
            ) : (
              <div className="space-y-3">
                {myBookings.map(b => (
                  <div key={b.id} className="rounded-xl p-4 border flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                    style={{ background: "var(--card)", borderColor: "var(--border)" }}>
                    <div className="flex items-start gap-3">
                      <div className="w-10 h-10 rounded-lg flex items-center justify-center text-lg shrink-0" style={{ background: "rgba(124,58,237,0.15)" }}>
                        🎤
                      </div>
                      <div>
                        <p className="text-white font-medium text-sm">{b.room}</p>
                        <p className="text-xs mt-0.5" style={{ color: "var(--muted-foreground)" }}>{b.date} · {b.startTime}–{b.endTime}</p>
                        <p className="text-xs mt-0.5" style={{ color: "var(--muted-foreground)", fontFamily: "monospace" }}>Ref: {b.refNumber}</p>
                      </div>
                    </div>
                    <div className="flex items-center gap-3 sm:flex-col sm:items-end">
                      <span style={{ fontFamily: "'Noe Display', serif", fontWeight: 700, color: "var(--accent)", fontSize: "1.1rem" }}>
                        ₱{b.totalCost.toLocaleString()}
                      </span>
                      <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusColor(b.status)}`}>{b.status}</span>
                      {(b.status === "Pending" || b.status === "Approved") && (
                        <button className="text-xs text-red-400 hover:text-red-300">Cancel</button>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
