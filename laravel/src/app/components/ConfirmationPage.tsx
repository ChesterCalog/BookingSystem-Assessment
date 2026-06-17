import { type Page } from "../App";

interface Props { navigate: (p: Page) => void; refNumber: string; }

export function ConfirmationPage({ navigate, refNumber }: Props) {
  return (
    <div className="pt-20 pb-16 min-h-screen flex items-center justify-center">
      <div className="max-w-lg mx-auto px-4 text-center">
        {/* Check icon */}
        <div className="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 border-2" style={{ background: "rgba(16,185,129,0.1)", borderColor: "rgba(16,185,129,0.4)" }}>
          <span className="text-5xl">✅</span>
        </div>

        <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "2.25rem", fontWeight: 700, color: "#fff", marginBottom: "0.5rem" }}>
          Booking Submitted!
        </h1>
        <p className="mb-8" style={{ color: "var(--muted-foreground)" }}>
          Your booking request is <span className="text-amber-400 font-medium">pending review</span>. A confirmation email has been sent to you.
        </p>

        {/* Ref box */}
        <div className="rounded-2xl p-6 mb-6 border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>
          <p className="text-xs font-semibold uppercase tracking-widest mb-2" style={{ color: "var(--muted-foreground)" }}>Your Reference Number</p>
          <p style={{ fontFamily: "monospace", fontSize: "2rem", fontWeight: 700, color: "var(--accent)" }}>{refNumber}</p>
          <p className="text-xs mt-2" style={{ color: "var(--muted-foreground)" }}>Keep this safe — you'll need it to check your booking status.</p>
        </div>

        {/* What happens next */}
        <div className="rounded-xl p-4 text-left mb-8 border" style={{ background: "rgba(59,130,246,0.08)", borderColor: "rgba(59,130,246,0.2)" }}>
          <p className="text-sm font-semibold text-blue-300 mb-2">ℹ️ What happens next?</p>
          <ul className="text-sm space-y-1.5" style={{ color: "var(--muted-foreground)" }}>
            <li>• Our team reviews your request within 1–2 hours.</li>
            <li>• You'll receive an email once approved or if changes are needed.</li>
            <li>• Payment is collected on-site on the day of your visit.</li>
            <li>• Bring a valid ID and your reference number.</li>
          </ul>
        </div>

        <div className="flex flex-col sm:flex-row gap-3 justify-center">
          <button onClick={() => navigate("home")} className="px-8 py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90" style={{ background: "var(--primary)" }}>
            Back to Home
          </button>
          <button onClick={() => navigate("rooms")} className="px-8 py-3 rounded-xl font-semibold border transition-all hover:text-white" style={{ borderColor: "rgba(124,58,237,0.4)", color: "#c4b5fd" }}>
            Browse More Rooms
          </button>
        </div>
      </div>
    </div>
  );
}
