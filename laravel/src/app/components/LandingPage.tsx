import { useState } from "react";
import { type Page } from "../App";
import { ROOMS } from "./data";

interface Props { navigate: (p: Page, id?: number) => void; }

const WHY = [
  { icon: "🔊", title: "Studio-Grade Audio", desc: "Crystal-clear microphones and immersive surround sound systems in every room." },
  { icon: "🔒", title: "Private Rooms", desc: "Total privacy for intimate gatherings, birthdays, and corporate events." },
  { icon: "🎵", title: "50,000+ Songs", desc: "Massive catalog spanning all genres, languages, and decades — updated weekly." },
  { icon: "🕐", title: "Flexible Hours", desc: "Open until 3AM on weekends. Book for as little as 1 hour with easy extensions." },
  { icon: "🍹", title: "F&B Service", desc: "Snacks, cocktails, and soft drinks delivered right to your room." },
  { icon: "📱", title: "Easy Online Booking", desc: "Reserve in under 2 minutes. No account required for guest bookings." },
];

const TESTIMONIALS = [
  { name: "Maria Santos", rating: 5, text: "Amazing experience! The VIP room was absolutely top-notch. Sound quality was incredible and the staff were so friendly.", role: "Event Organizer" },
  { name: "James Reyes", rating: 5, text: "Booked the Grand Arena for my birthday and everyone went crazy. The party lights and disco ball made it unforgettable!", role: "Regular Customer" },
  { name: "Ana Cruz", rating: 4, text: "Clean, well-maintained rooms with awesome song selections. Prices are very reasonable. Will definitely be back!", role: "Music Teacher" },
  { name: "Carlo Mendoza", rating: 5, text: "The online booking is super convenient. Room was exactly as pictured and ready when we arrived. Loved it!", role: "Student" },
];

const FAQS = [
  ["How do I make a reservation?", "You can book online through our website in minutes — no account needed. Online booking is recommended to secure your preferred time slot."],
  ["Can I book without an account?", "Yes! Guest bookings only require your name, email, and phone number. Registered members get booking history and easier cancellations."],
  ["What is your cancellation policy?", "Cancellations made at least 24 hours before your booking are free of charge. Late cancellations may incur a 50% fee."],
  ["Do you provide food and drinks?", "Yes — we offer snacks, cocktails, and beverages. Outside food is also welcome for private celebrations."],
  ["What's the minimum booking time?", "Minimum is 1 hour. You can extend in 30-minute increments if the room is still available."],
  ["How many people fit per room?", "Rooms range from Small (2–4 pax) to XL Party (up to 30 pax). Check our Rooms page for full details."],
];

export function LandingPage({ navigate }: Props) {
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const featured = ROOMS.slice(0, 3);

  return (
    <div>
      {/* ── HERO ─────────────────────────────────────────────────── */}
      <section className="relative min-h-screen flex items-center justify-center pt-16 overflow-hidden">
        {/* Background image with overlay */}
        <div className="absolute inset-0">
          <img
            src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=1600&h=900&fit=crop&auto=format"
            alt="Karaoke stage with colorful lights"
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0" style={{ background: "linear-gradient(to bottom, rgba(10,6,20,0.75) 0%, rgba(10,6,20,0.55) 50%, rgba(10,6,20,0.9) 100%)" }} />
        </div>

        {/* Ambient glow */}
        <div className="absolute inset-0 pointer-events-none overflow-hidden">
          <div className="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full" style={{ background: "radial-gradient(circle, rgba(124,58,237,0.25) 0%, transparent 70%)" }} />
        </div>

        <div className="relative z-10 max-w-5xl mx-auto px-4 text-center">
          <div className="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm mb-6 border" style={{ background: "rgba(124,58,237,0.15)", borderColor: "rgba(124,58,237,0.3)", color: "#c4b5fd" }}>
            <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
            Now Open — Book Your Room Today
          </div>

          <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2.8rem,7vw,5.5rem)", fontWeight: 700, lineHeight: 1.1, color: "#fff", marginBottom: "1.5rem" }}>
            Unleash Your<br />
            <span style={{ background: "linear-gradient(135deg,#a78bfa 0%,#f59e0b 100%)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>
              Inner Superstar
            </span>
          </h1>

          <p className="text-slate-300 max-w-xl mx-auto mb-10" style={{ fontSize: "1.125rem", lineHeight: 1.7 }}>
            Premium private karaoke rooms for every occasion. Book online in minutes and get ready for an unforgettable night of singing and laughter.
          </p>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button
              onClick={() => navigate("guest-book")}
              className="px-8 py-4 rounded-xl font-semibold text-white text-lg transition-all hover:opacity-90 hover:-translate-y-0.5"
              style={{ background: "var(--primary)", boxShadow: "0 8px 30px rgba(124,58,237,0.4)" }}
            >
              🎤 Book a Room Now
            </button>
            <button
              onClick={() => navigate("rooms")}
              className="px-8 py-4 rounded-xl font-semibold text-slate-200 text-lg transition-all hover:text-white border"
              style={{ borderColor: "rgba(255,255,255,0.2)", background: "rgba(255,255,255,0.06)" }}
            >
              View All Rooms
            </button>
          </div>

          {/* Stats row */}
          <div className="mt-16 grid grid-cols-3 gap-4 max-w-md mx-auto">
            {[["50K+", "Songs"], ["10", "Rooms"], ["10K+", "Singers"]].map(([num, label]) => (
              <div key={label} className="rounded-xl p-4 text-center border" style={{ background: "rgba(124,58,237,0.1)", borderColor: "rgba(124,58,237,0.2)" }}>
                <p style={{ fontFamily: "'Noe Display', serif", fontSize: "1.75rem", fontWeight: 700, color: "#fff" }}>{num}</p>
                <p className="text-xs mt-0.5" style={{ color: "#8b7ab8" }}>{label}</p>
              </div>
            ))}
          </div>
        </div>

        {/* Scroll cue */}
        <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce opacity-50">
          <svg width="20" height="20" fill="none" stroke="white" strokeWidth="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12l7 7 7-7" /></svg>
        </div>
      </section>

      {/* ── WHY CHOOSE US ─────────────────────────────────────────── */}
      <section className="py-24" style={{ background: "var(--card)" }}>
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-14">
            <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2rem,4vw,2.75rem)", fontWeight: 700, color: "#fff" }}>
              Why Choose <span style={{ background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>KaraokeZone?</span>
            </h2>
            <p className="mt-3 max-w-lg mx-auto" style={{ color: "var(--muted-foreground)" }}>
              We go beyond just karaoke. Every detail is designed for your perfect night out.
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {WHY.map(({ icon, title, desc }) => (
              <div key={title} className="rounded-2xl p-6 transition-all hover:-translate-y-1 border" style={{ background: "var(--background)", borderColor: "var(--border)" }}>
                <div className="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4" style={{ background: "rgba(124,58,237,0.12)" }}>
                  {icon}
                </div>
                <h3 className="font-semibold text-white mb-2">{title}</h3>
                <p className="text-sm leading-relaxed" style={{ color: "var(--muted-foreground)" }}>{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── FEATURED ROOMS ───────────────────────────────────────── */}
      <section className="py-24">
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-14">
            <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2rem,4vw,2.75rem)", fontWeight: 700, color: "#fff" }}>
              Our <span style={{ background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>Rooms</span>
            </h2>
            <p className="mt-3 max-w-lg mx-auto" style={{ color: "var(--muted-foreground)" }}>
              From cozy duet booths to massive party halls — a room for every occasion.
            </p>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {featured.map(room => (
              <div key={room.id} className="rounded-2xl overflow-hidden border group cursor-pointer transition-all hover:-translate-y-1"
                style={{ background: "var(--card)", borderColor: "var(--border)" }}
                onClick={() => navigate("room-detail", room.id)}>
                <div className="relative h-48 overflow-hidden bg-slate-900">
                  <img src={room.image} alt={room.name} className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                  <div className="absolute inset-0" style={{ background: "linear-gradient(to top, rgba(10,6,20,0.6) 0%, transparent 60%)" }} />
                  <span className="absolute top-3 left-3 px-2.5 py-1 rounded-full text-xs font-semibold text-white" style={{ background: "var(--primary)" }}>
                    {room.type}
                  </span>
                  <span className="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs" style={{ background: "rgba(10,6,20,0.7)", color: "#e2e8f0" }}>
                    👥 {room.capacity} pax
                  </span>
                </div>
                <div className="p-5">
                  <h3 className="font-semibold text-white text-lg mb-1">{room.name}</h3>
                  <p className="text-sm mb-3 line-clamp-2" style={{ color: "var(--muted-foreground)" }}>{room.description}</p>
                  <div className="flex flex-wrap gap-1 mb-4">
                    {room.amenities.slice(0, 3).map(a => (
                      <span key={a} className="text-xs px-2 py-0.5 rounded-full border" style={{ background: "rgba(124,58,237,0.1)", borderColor: "rgba(124,58,237,0.2)", color: "#c4b5fd" }}>{a}</span>
                    ))}
                  </div>
                  <div className="flex items-center justify-between pt-3 border-t" style={{ borderColor: "var(--border)" }}>
                    <div>
                      <span style={{ fontFamily: "'Noe Display', serif", fontSize: "1.5rem", fontWeight: 700, color: "var(--accent)" }}>
                        ₱{room.pricePerHour.toLocaleString()}
                      </span>
                      <span className="text-sm ml-1" style={{ color: "var(--muted-foreground)" }}>/hr</span>
                    </div>
                    <span className="text-sm font-medium" style={{ color: "var(--primary)" }}>View Room →</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
          <div className="text-center mt-10">
            <button onClick={() => navigate("rooms")} className="px-8 py-3 rounded-xl font-semibold border transition-all hover:text-white" style={{ borderColor: "rgba(124,58,237,0.4)", color: "#a78bfa" }}>
              View All 6 Rooms →
            </button>
          </div>
        </div>
      </section>

      {/* ── TESTIMONIALS ─────────────────────────────────────────── */}
      <section className="py-24" style={{ background: "var(--card)" }}>
        <div className="max-w-6xl mx-auto px-4">
          <div className="text-center mb-14">
            <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2rem,4vw,2.75rem)", fontWeight: 700, color: "#fff" }}>
              What Our Customers <span style={{ background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>Say</span>
            </h2>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {TESTIMONIALS.map(({ name, rating, text, role }) => (
              <div key={name} className="rounded-2xl p-6 border" style={{ background: "var(--background)", borderColor: "var(--border)" }}>
                <div className="flex gap-0.5 mb-3">
                  {Array.from({ length: rating }).map((_, i) => <span key={i} className="text-amber-400 text-sm">★</span>)}
                </div>
                <p className="text-sm leading-relaxed mb-4 italic" style={{ color: "var(--muted-foreground)" }}>"{text}"</p>
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold" style={{ background: "var(--primary)" }}>
                    {name[0]}
                  </div>
                  <div>
                    <p className="text-white text-sm font-medium">{name}</p>
                    <p className="text-xs" style={{ color: "var(--muted-foreground)" }}>{role}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── FAQ ──────────────────────────────────────────────────── */}
      <section className="py-24">
        <div className="max-w-3xl mx-auto px-4">
          <div className="text-center mb-14">
            <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2rem,4vw,2.75rem)", fontWeight: 700, color: "#fff" }}>
              Frequently Asked <span style={{ background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>Questions</span>
            </h2>
          </div>
          <div className="space-y-3">
            {FAQS.map(([q, a], i) => (
              <div key={i} className="rounded-xl border overflow-hidden" style={{ borderColor: "var(--border)", background: "var(--card)" }}>
                <button onClick={() => setOpenFaq(openFaq === i ? null : i)} className="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-white/5 transition-colors">
                  <span className="font-medium text-white text-sm">{q}</span>
                  <span className="ml-4 text-violet-400 transition-transform shrink-0" style={{ transform: openFaq === i ? "rotate(180deg)" : "none" }}>▼</span>
                </button>
                {openFaq === i && (
                  <div className="px-5 pb-4">
                    <p className="text-sm leading-relaxed" style={{ color: "var(--muted-foreground)" }}>{a}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── CTA BANNER ───────────────────────────────────────────── */}
      <section className="py-20 border-y" style={{ borderColor: "rgba(124,58,237,0.2)", background: "linear-gradient(135deg, rgba(124,58,237,0.1) 0%, rgba(245,158,11,0.05) 100%)" }}>
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h2 style={{ fontFamily: "'Noe Display', serif", fontSize: "clamp(2rem,4vw,3rem)", fontWeight: 700, color: "#fff", marginBottom: "1rem" }}>
            Ready to Sing? 🎤
          </h2>
          <p className="text-slate-300 text-lg mb-8">Book your karaoke room now. No account required!</p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button onClick={() => navigate("guest-book")} className="px-10 py-4 rounded-xl font-semibold text-white text-lg transition-all hover:opacity-90" style={{ background: "var(--primary)", boxShadow: "0 8px 30px rgba(124,58,237,0.35)" }}>
              Book as Guest
            </button>
            <button onClick={() => navigate("register")} className="px-10 py-4 rounded-xl font-semibold text-lg transition-all hover:text-white border" style={{ borderColor: "rgba(245,158,11,0.4)", color: "var(--accent)" }}>
              Create an Account
            </button>
          </div>
        </div>
      </section>

      {/* ── FOOTER ───────────────────────────────────────────────── */}
      <footer className="py-12 border-t" style={{ borderColor: "rgba(124,58,237,0.15)", background: "var(--card)" }}>
        <div className="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <div className="flex items-center gap-2 mb-3">
              <span className="text-2xl">🎤</span>
              <span style={{ fontFamily: "'Noe Display', serif", fontWeight: 700, background: "linear-gradient(135deg,#a78bfa,#f59e0b)", WebkitBackgroundClip: "text", WebkitTextFillColor: "transparent", backgroundClip: "text" }}>KaraokeZone</span>
            </div>
            <p className="text-sm leading-relaxed" style={{ color: "var(--muted-foreground)" }}>Your premier karaoke destination. Sing, celebrate, and create unforgettable memories.</p>
          </div>
          {[
            ["Quick Links", [["Home", "home"], ["Rooms", "rooms"], ["Book a Room", "guest-book"]] as [string, Page][]],
            ["Contact Us", null],
            ["Hours", null],
          ].map(([title]) => (
            <div key={title as string}>
              <h4 className="font-semibold text-white mb-3 text-sm">{title as string}</h4>
              {title === "Quick Links" && (
                <ul className="space-y-2">
                  {(["Home", "Rooms", "Book a Room"] as const).map((label, i) => (
                    <li key={label}><button onClick={() => navigate(["home","rooms","guest-book"][i] as Page)} className="text-sm transition-colors hover:text-white" style={{ color: "var(--muted-foreground)" }}>{label}</button></li>
                  ))}
                </ul>
              )}
              {title === "Contact Us" && (
                <ul className="space-y-2 text-sm" style={{ color: "var(--muted-foreground)" }}>
                  <li>📍 123 Karaoke St., Manila</li>
                  <li>📞 +63 912 345 6789</li>
                  <li>✉️ hello@karaokeZone.com</li>
                </ul>
              )}
              {title === "Hours" && (
                <ul className="space-y-1 text-sm" style={{ color: "var(--muted-foreground)" }}>
                  <li>Mon–Thu: 2PM – 12AM</li>
                  <li>Fri–Sat: 12PM – 3AM</li>
                  <li>Sunday: 12PM – 12AM</li>
                </ul>
              )}
            </div>
          ))}
        </div>
        <div className="max-w-6xl mx-auto px-4 mt-8 pt-6 border-t text-center text-xs" style={{ borderColor: "rgba(124,58,237,0.15)", color: "var(--muted-foreground)" }}>
          © 2025 KaraokeZone. All rights reserved.
        </div>
      </footer>
    </div>
  );
}
