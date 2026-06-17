/**
 * BookingPage — Unified booking flow for both Guests and Registered Users.
 *
 * Booking type (Guest / User) is selected on Step 0 via a segmented toggle.
 * All sub-components are at MODULE SCOPE to prevent focus loss on re-renders.
 * All change-handlers use useCallback for stable references.
 */
import { useState, useCallback, useEffect } from "react";
import { type Page, type AppUser } from "../App";
import {
  ROOMS,
  SAMPLE_BOOKINGS,
  calcCost,
  fmtHour,
  hasConflict,
  type Booking,
} from "./data";
import { BookingCalendar, type CalendarSelection } from "./BookingCalendar";

// ─────────────────────────────────────────────────────────────────
// Module-scope layout atoms  (NEVER define these inside a component)
// ─────────────────────────────────────────────────────────────────

function Section({ children }: { children: React.ReactNode }) {
  return (
    <div
      className="rounded-2xl p-6 sm:p-8 border"
      style={{ background: "var(--card)", borderColor: "var(--border)" }}
    >
      {children}
    </div>
  );
}

function StepBar({ step, steps }: { step: number; steps: readonly string[] }) {
  return (
    <div className="flex items-center justify-center mb-8 select-none">
      {steps.map((label, i) => (
        <div key={label} className="flex items-center">
          <div className="flex items-center gap-2">
            <div
              className="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
              style={{
                background: i <= step ? "var(--primary)" : "rgba(255,255,255,0.08)",
                color: i <= step ? "#fff" : "var(--muted-foreground)",
                border: i === step ? "2px solid rgba(167,139,250,0.5)" : "none",
              }}
            >
              {i < step ? "✓" : i + 1}
            </div>
            <span
              className="text-xs hidden sm:block"
              style={{
                color: i === step ? "#fff" : "var(--muted-foreground)",
                fontWeight: i === step ? 600 : 400,
              }}
            >
              {label}
            </span>
          </div>
          {i < steps.length - 1 && (
            <div
              className="w-8 sm:w-14 h-px mx-2 transition-colors"
              style={{ background: i < step ? "var(--primary)" : "rgba(255,255,255,0.1)" }}
            />
          )}
        </div>
      ))}
    </div>
  );
}

/** Segmented control for switching booking mode */
function BookingTypeToggle({
  mode,
  user,
  onSelect,
}: {
  mode: "guest" | "user";
  user: AppUser | null;
  onSelect: (m: "guest" | "user") => void;
}) {
  return (
    <div className="mb-6">
      <p className="text-sm font-medium text-white mb-3">How would you like to book?</p>
      <div
        className="flex rounded-xl p-1 gap-1"
        style={{ background: "rgba(255,255,255,0.05)", border: "1px solid var(--border)" }}
      >
        {(["guest", "user"] as const).map((m) => {
          const active = mode === m;
          const label  = m === "guest" ? "Book as Guest" : "Book as Registered User";
          const icon   = m === "guest" ? "👤" : "🎫";
          const sub    = m === "guest"
            ? "No account needed"
            : user ? `Signed in as ${user.name.split(" ")[0]}` : "Login required";

          return (
            <button
              key={m}
              type="button"
              onClick={() => onSelect(m)}
              className="flex-1 flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-left"
              style={{
                background: active ? "var(--primary)" : "transparent",
                boxShadow: active ? "0 4px 16px rgba(124,58,237,0.3)" : "none",
              }}
            >
              <span className="text-xl shrink-0">{icon}</span>
              <div>
                <p
                  className="text-sm font-semibold leading-tight"
                  style={{ color: active ? "#fff" : "var(--muted-foreground)" }}
                >
                  {label}
                </p>
                <p
                  className="text-xs mt-0.5"
                  style={{ color: active ? "rgba(255,255,255,0.75)" : "rgba(148,163,184,0.6)" }}
                >
                  {sub}
                </p>
              </div>
              {active && (
                <span className="ml-auto text-white/80 text-xs shrink-0">✓</span>
              )}
            </button>
          );
        })}
      </div>
    </div>
  );
}

/** Shown inside "Book as User" when NOT logged in */
function LoginPrompt({ navigate }: { navigate: (p: Page) => void }) {
  return (
    <div
      className="rounded-2xl p-6 border text-center"
      style={{ background: "rgba(124,58,237,0.06)", borderColor: "rgba(124,58,237,0.25)" }}
    >
      <div
        className="w-14 h-14 rounded-full flex items-center justify-center text-2xl mx-auto mb-4"
        style={{ background: "rgba(124,58,237,0.15)" }}
      >
        🔒
      </div>
      <h3
        className="font-semibold text-white text-base mb-2"
        style={{ fontFamily: "'Noe Display', serif" }}
      >
        Sign in to continue
      </h3>
      <p className="text-sm mb-5" style={{ color: "var(--muted-foreground)" }}>
        Please log in or create an account to book as a registered user.
        <br />
        You can also <span style={{ color: "#f59e0b" }}>Book as Guest</span> without signing in.
      </p>
      <div className="flex flex-col sm:flex-row gap-3 justify-center">
        <button
          type="button"
          onClick={() => navigate("login")}
          className="px-6 py-2.5 rounded-xl font-semibold text-white transition-all hover:opacity-90"
          style={{ background: "var(--primary)" }}
        >
          Sign In
        </button>
        <button
          type="button"
          onClick={() => navigate("register")}
          className="px-6 py-2.5 rounded-xl font-semibold border transition-all hover:text-white"
          style={{ borderColor: "rgba(124,58,237,0.4)", color: "#c4b5fd" }}
        >
          Create Account
        </button>
      </div>
    </div>
  );
}

/** Shown inside "Book as User" when logged in */
function UserCard({ user }: { user: AppUser }) {
  return (
    <div
      className="flex items-center gap-4 p-4 rounded-2xl border"
      style={{ background: "rgba(124,58,237,0.08)", borderColor: "rgba(124,58,237,0.25)" }}
    >
      <div
        className="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold text-white shrink-0"
        style={{ background: "var(--primary)" }}
      >
        {user.name[0]}
      </div>
      <div className="flex-1 min-w-0">
        <p className="text-white font-semibold truncate">{user.name}</p>
        <p className="text-xs truncate" style={{ color: "var(--muted-foreground)" }}>{user.email}</p>
      </div>
      <span
        className="text-xs px-2.5 py-1 rounded-full shrink-0 flex items-center gap-1"
        style={{ background: "rgba(52,211,153,0.12)", color: "#34d399", border: "1px solid rgba(52,211,153,0.25)" }}
      >
        <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
        Logged in
      </span>
    </div>
  );
}

// ─────────────────────────────────────────────────────────────────
// Shared input styles (module-scope constants)
// ─────────────────────────────────────────────────────────────────

const INPUT_CLS   = "w-full rounded-xl px-4 py-3 text-white text-sm border focus:outline-none placeholder:text-slate-600";
const INPUT_STYLE: React.CSSProperties = {
  background: "var(--input-background)",
  borderColor: "var(--border)",
};

const STEPS = ["Details & Room", "Date & Time", "Review & Confirm"] as const;

// ─────────────────────────────────────────────────────────────────
// Main component
// ─────────────────────────────────────────────────────────────────

export interface BookingPageProps {
  navigate: (p: Page, id?: number) => void;
  user: AppUser | null;
  selectedRoomId: number;
  confirmBooking: (ref: string) => void;
  /** Pre-select booking mode when navigating from a specific entry point */
  defaultMode?: "guest" | "user";
}

export function BookingPage({
  navigate,
  user,
  selectedRoomId,
  confirmBooking,
  defaultMode,
}: BookingPageProps) {
  // If user is logged in, default to "user" mode regardless of prop
  const resolvedDefault: "guest" | "user" = user ? "user" : (defaultMode ?? "guest");

  const [step,    setStep]    = useState<0 | 1 | 2>(0);
  const [mode,    setMode]    = useState<"guest" | "user">(resolvedDefault);
  const [roomId,  setRoomId]  = useState<number>(selectedRoomId || ROOMS[0].id);
  const [guests,  setGuests]  = useState<number>(1);
  const [remarks, setRemarks] = useState<string>("");
  const [errors,  setErrors]  = useState<Record<string, string>>({});
  const [bookings]            = useState<Booking[]>(SAMPLE_BOOKINGS);
  const [cal,     setCal]     = useState<CalendarSelection>({
    date: "", startHour: null, endHour: null,
  });

  // Guest-specific fields
  const [guestName,  setGuestName]  = useState<string>("");
  const [guestPhone, setGuestPhone] = useState<string>("");
  const [guestEmail, setGuestEmail] = useState<string>("");

  // If user logs in mid-flow, auto-upgrade to user mode
  useEffect(() => {
    if (user) setMode("user");
  }, [user]);

  // Reset room calendar when room changes
  const handleRoomChange = useCallback((id: number) => {
    setRoomId(id);
    setCal({ date: "", startHour: null, endHour: null });
  }, []);

  const handleCalChange = useCallback((v: CalendarSelection) => {
    setCal(v);
    setErrors((e) => ({ ...e, date: "", time: "" }));
  }, []);

  const handleModeChange = useCallback((m: "guest" | "user") => {
    setMode(m);
    setErrors({});
  }, []);

  // Stable guest-field handlers
  const handleGuestName  = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setGuestName(e.target.value);  setErrors(p => ({ ...p, guestName: "" })); }, []);
  const handleGuestPhone = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setGuestPhone(e.target.value); setErrors(p => ({ ...p, guestPhone: "" })); }, []);
  const handleGuestEmail = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setGuestEmail(e.target.value); setErrors(p => ({ ...p, guestEmail: "" })); }, []);
  const handleRemarks    = useCallback((e: React.ChangeEvent<HTMLTextAreaElement>) => setRemarks(e.target.value), []);

  const incGuests = useCallback(() => setGuests(g => Math.min(room.capacity, g + 1)), []); // eslint-disable-line
  const decGuests = useCallback(() => setGuests(g => Math.max(1, g - 1)), []);
  const handleGuestCount = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    const v = parseInt(e.target.value, 10);
    if (!Number.isNaN(v)) setGuests(Math.max(1, v));
  }, []);

  const room = ROOMS.find((r) => r.id === roomId) ?? ROOMS[0];
  const hoursCount = cal.startHour !== null && cal.endHour !== null
    ? calcCost(1, cal.startHour, cal.endHour) : 0;
  const cost = hoursCount * room.pricePerHour;

  // ── Validation ──────────────────────────────────────────────

  const validateStep0 = (): boolean => {
    const e: Record<string, string> = {};

    if (mode === "guest") {
      if (!guestName.trim())  e.guestName  = "Full name is required.";
      if (!guestPhone.trim()) e.guestPhone = "Contact number is required.";
      if (guestEmail && !guestEmail.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/))
        e.guestEmail = "Enter a valid email address.";
    }
    if (mode === "user" && !user) {
      e.auth = "Please sign in to continue as a registered user.";
    }
    if (guests < 1)              e.guests = "At least 1 guest is required.";
    if (guests > room.capacity)  e.guests = `This room holds max ${room.capacity} guests.`;

    setErrors(e);
    return Object.keys(e).length === 0;
  };

  const validateStep1 = (): boolean => {
    const e: Record<string, string> = {};
    if (!cal.date)                   e.date = "Please select a booking date.";
    else if (cal.startHour === null)  e.time = "Please select a start time slot.";
    else if (cal.endHour === null)    e.time = "Please select an end time slot.";
    else if (hasConflict(room.id, cal.date, cal.startHour, cal.endHour, bookings))
      e.time = "This slot overlaps an existing reservation. Please pick another time.";
    setErrors(e);
    return Object.keys(e).length === 0;
  };

  const goNext = () => {
    if (step === 0 && validateStep0()) setStep(1);
    if (step === 1 && validateStep1()) setStep(2);
  };
  const goBack = () => setStep((s) => (s > 0 ? ((s - 1) as 0 | 1 | 2) : 0));
  const submit = () => {
    const prefix = mode === "guest" ? "GB" : "BK";
    confirmBooking(`${prefix}-${Math.random().toString(36).substring(2, 10).toUpperCase()}`);
  };

  // Derived display name/email for the review step
  const displayName  = mode === "user" ? (user?.name  ?? "—") : guestName;
  const displayEmail = mode === "user" ? (user?.email ?? "—") : (guestEmail || "—");

  return (
    <div className="pt-20 pb-16 min-h-screen">
      <div className="max-w-3xl mx-auto px-4 py-8">

        {/* Page header */}
        <div className="text-center mb-6">
          <h1
            style={{
              fontFamily: "'Noe Display', serif",
              fontSize: "2.25rem",
              fontWeight: 700,
              color: "#fff",
            }}
          >
            Book a{" "}
            <span
              style={{
                background: "linear-gradient(135deg,#a78bfa,#f59e0b)",
                WebkitBackgroundClip: "text",
                WebkitTextFillColor: "transparent",
                backgroundClip: "text",
              }}
            >
              Room
            </span>
          </h1>
          <p className="mt-1 text-sm" style={{ color: "var(--muted-foreground)" }}>
            Select your booking type, pick a room, and choose your date.
          </p>
        </div>

        <StepBar step={step} steps={STEPS} />

        {/* ── STEP 0: Booking type + Room + Guest count ─────── */}
        {step === 0 && (
          <Section>
            {/* Booking type toggle */}
            <BookingTypeToggle mode={mode} user={user} onSelect={handleModeChange} />

            {/* Guest fields OR user card */}
            {mode === "guest" ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 pt-1 border-t" style={{ borderColor: "var(--border)" }}>
                <p className="sm:col-span-2 text-xs pt-3" style={{ color: "var(--muted-foreground)" }}>
                  Your contact details (no account needed)
                </p>

                {/* Full Name */}
                <div className="sm:col-span-2">
                  <label htmlFor="guest-name" className="block text-sm font-medium text-white mb-2">
                    Full Name <span className="text-red-400">*</span>
                  </label>
                  <input
                    id="guest-name"
                    type="text"
                    value={guestName}
                    onChange={handleGuestName}
                    placeholder="Juan dela Cruz"
                    autoComplete="name"
                    className={INPUT_CLS}
                    style={INPUT_STYLE}
                  />
                  {errors.guestName && (
                    <p className="text-red-400 text-xs mt-1">{errors.guestName}</p>
                  )}
                </div>

                {/* Phone */}
                <div>
                  <label htmlFor="guest-phone" className="block text-sm font-medium text-white mb-2">
                    Contact Number <span className="text-red-400">*</span>
                  </label>
                  <input
                    id="guest-phone"
                    type="tel"
                    value={guestPhone}
                    onChange={handleGuestPhone}
                    placeholder="+63 912 345 6789"
                    autoComplete="tel"
                    className={INPUT_CLS}
                    style={INPUT_STYLE}
                  />
                  {errors.guestPhone && (
                    <p className="text-red-400 text-xs mt-1">{errors.guestPhone}</p>
                  )}
                </div>

                {/* Email (optional) */}
                <div>
                  <label htmlFor="guest-email" className="block text-sm font-medium text-white mb-2">
                    Email Address{" "}
                    <span className="font-normal text-xs" style={{ color: "var(--muted-foreground)" }}>(optional)</span>
                  </label>
                  <input
                    id="guest-email"
                    type="email"
                    value={guestEmail}
                    onChange={handleGuestEmail}
                    placeholder="for booking confirmation"
                    autoComplete="email"
                    className={INPUT_CLS}
                    style={INPUT_STYLE}
                  />
                  {errors.guestEmail && (
                    <p className="text-red-400 text-xs mt-1">{errors.guestEmail}</p>
                  )}
                </div>
              </div>
            ) : (
              <div className="mb-6 pt-1 border-t" style={{ borderColor: "var(--border)" }}>
                <p className="text-xs pt-3 mb-3" style={{ color: "var(--muted-foreground)" }}>
                  Your account information
                </p>
                {user ? (
                  <UserCard user={user} />
                ) : (
                  <LoginPrompt navigate={navigate} />
                )}
                {errors.auth && (
                  <p className="text-red-400 text-xs mt-2">{errors.auth}</p>
                )}
              </div>
            )}

            {/* Divider */}
            <hr className="my-5" style={{ borderColor: "var(--border)" }} />

            {/* Room selector */}
            <div className="mb-5">
              <label className="block text-sm font-medium text-white mb-3">
                Select Room <span className="text-red-400">*</span>
              </label>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {ROOMS.filter((r) => r.available).map((r) => (
                  <button
                    key={r.id}
                    type="button"
                    onClick={() => handleRoomChange(r.id)}
                    className="flex items-center gap-3 p-3 rounded-xl border text-left transition-all hover:border-violet-500/50"
                    style={{
                      background: roomId === r.id
                        ? "rgba(124,58,237,0.15)"
                        : "rgba(255,255,255,0.03)",
                      borderColor: roomId === r.id
                        ? "rgba(124,58,237,0.5)"
                        : "var(--border)",
                    }}
                  >
                    <img
                      src={r.image}
                      alt={r.name}
                      className="w-12 h-12 rounded-lg object-cover shrink-0"
                    />
                    <div className="overflow-hidden">
                      <p className="text-white text-sm font-medium truncate">{r.name}</p>
                      <p className="text-xs" style={{ color: "var(--muted-foreground)" }}>
                        {r.type} · {r.capacity} pax · ₱{r.pricePerHour.toLocaleString()}/hr
                      </p>
                    </div>
                    {roomId === r.id && (
                      <span className="ml-auto text-violet-400 shrink-0 text-sm">✓</span>
                    )}
                  </button>
                ))}
              </div>
            </div>

            {/* Guest count */}
            <div className="mb-5">
              <label className="block text-sm font-medium text-white mb-2">
                Number of Guests <span className="text-red-400">*</span>
                <span
                  className="font-normal text-xs ml-2"
                  style={{ color: "var(--muted-foreground)" }}
                >
                  (max {room.capacity})
                </span>
              </label>
              <div className="flex items-center gap-3">
                <button
                  type="button"
                  onClick={decGuests}
                  className="w-10 h-10 rounded-xl flex items-center justify-center text-xl font-bold transition-colors hover:bg-white/10 border"
                  style={{ borderColor: "var(--border)", color: "#c4b5fd" }}
                >
                  −
                </button>
                <input
                  type="number"
                  value={guests}
                  onChange={handleGuestCount}
                  min={1}
                  max={room.capacity}
                  className="w-20 text-center rounded-xl py-2.5 text-white text-lg font-bold border focus:outline-none"
                  style={{ background: "var(--input-background)", borderColor: "var(--border)" }}
                />
                <button
                  type="button"
                  onClick={incGuests}
                  className="w-10 h-10 rounded-xl flex items-center justify-center text-xl font-bold transition-colors hover:bg-white/10 border"
                  style={{ borderColor: "var(--border)", color: "#c4b5fd" }}
                >
                  +
                </button>
                <div
                  className="ml-1 h-2 flex-1 rounded-full overflow-hidden"
                  style={{ background: "rgba(255,255,255,0.08)" }}
                >
                  <div
                    className="h-full rounded-full transition-all"
                    style={{
                      width: `${Math.min(100, (guests / room.capacity) * 100)}%`,
                      background: guests > room.capacity * 0.85 ? "#f59e0b" : "var(--primary)",
                    }}
                  />
                </div>
                <span className="text-xs shrink-0" style={{ color: "var(--muted-foreground)" }}>
                  {guests}/{room.capacity}
                </span>
              </div>
              {errors.guests && (
                <p className="text-red-400 text-xs mt-2">{errors.guests}</p>
              )}
            </div>

            {/* Special requests */}
            <div>
              <label className="block text-sm font-medium text-white mb-2">
                Special Requests{" "}
                <span className="font-normal text-xs" style={{ color: "var(--muted-foreground)" }}>
                  (optional)
                </span>
              </label>
              <textarea
                value={remarks}
                onChange={handleRemarks}
                rows={3}
                placeholder="Song preferences, decorations, dietary needs…"
                className={INPUT_CLS + " resize-none"}
                style={INPUT_STYLE}
              />
            </div>

            <div className="mt-6 flex justify-end">
              <button
                type="button"
                onClick={goNext}
                className="px-8 py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90"
                style={{ background: "var(--primary)" }}
              >
                Next: Select Date & Time →
              </button>
            </div>
          </Section>
        )}

        {/* ── STEP 1: Calendar ─────────────────────────────── */}
        {step === 1 && (
          <Section>
            {/* Room context badge */}
            <div className="flex items-center justify-between mb-5">
              <h2 className="font-semibold text-white text-lg">Select Date & Time</h2>
              <div
                className="flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs shrink-0"
                style={{
                  borderColor: "var(--border)",
                  color: "var(--muted-foreground)",
                  background: "rgba(255,255,255,0.03)",
                }}
              >
                <img
                  src={room.image}
                  alt={room.name}
                  className="w-5 h-5 rounded object-cover"
                />
                <span className="hidden sm:inline max-w-[140px] truncate">{room.name}</span>
                {/* Booking mode pill */}
                <span
                  className="ml-1 px-2 py-0.5 rounded-full text-[10px] font-semibold"
                  style={{
                    background: mode === "user"
                      ? "rgba(52,211,153,0.12)"
                      : "rgba(245,158,11,0.12)",
                    color: mode === "user" ? "#34d399" : "#f59e0b",
                  }}
                >
                  {mode === "user" ? "Member" : "Guest"}
                </span>
              </div>
            </div>

            <BookingCalendar
              room={room}
              existingBookings={bookings}
              value={cal}
              onChange={handleCalChange}
            />

            {errors.date && (
              <p className="text-red-400 text-xs mt-3">{errors.date}</p>
            )}
            {errors.time && (
              <div
                className="mt-3 px-4 py-3 rounded-xl text-sm border flex items-start gap-2"
                style={{
                  background: "rgba(239,68,68,0.08)",
                  borderColor: "rgba(239,68,68,0.25)",
                  color: "#fca5a5",
                }}
              >
                <span className="shrink-0">⚠️</span>
                <span>{errors.time}</span>
              </div>
            )}

            <div className="mt-6 flex items-center justify-between gap-3">
              <button
                type="button"
                onClick={goBack}
                className="px-6 py-3 rounded-xl font-medium border transition-colors hover:text-white"
                style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}
              >
                ← Back
              </button>
              <button
                type="button"
                onClick={goNext}
                disabled={!cal.date || cal.startHour === null || cal.endHour === null}
                className="px-8 py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed"
                style={{ background: "var(--primary)" }}
              >
                Next: Review →
              </button>
            </div>
          </Section>
        )}

        {/* ── STEP 2: Review & Confirm ─────────────────────── */}
        {step === 2 && (
          <Section>
            <h2 className="font-semibold text-white text-lg mb-6">Review Your Booking</h2>

            {/* Booking type badge */}
            <div
              className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4 border"
              style={{
                background: mode === "user"
                  ? "rgba(52,211,153,0.1)"
                  : "rgba(245,158,11,0.1)",
                borderColor: mode === "user"
                  ? "rgba(52,211,153,0.25)"
                  : "rgba(245,158,11,0.25)",
                color: mode === "user" ? "#34d399" : "#f59e0b",
              }}
            >
              <span>{mode === "user" ? "🎫" : "👤"}</span>
              {mode === "user" ? "Registered Member Booking" : "Guest Booking"}
            </div>

            {/* Room card */}
            <div
              className="flex items-center gap-4 p-4 rounded-xl border mb-5"
              style={{ background: "rgba(124,58,237,0.06)", borderColor: "rgba(124,58,237,0.2)" }}
            >
              <img
                src={room.image}
                alt={room.name}
                className="w-16 h-16 rounded-xl object-cover shrink-0"
              />
              <div>
                <p className="text-white font-semibold">{room.name}</p>
                <p className="text-sm" style={{ color: "var(--muted-foreground)" }}>
                  {room.type} · {room.size} · {room.capacity} pax max
                </p>
              </div>
            </div>

            {/* Details grid */}
            <div className="grid grid-cols-2 gap-3 text-sm mb-5">
              {[
                {
                  label: "Date",
                  value: cal.date
                    ? new Date(cal.date + "T12:00:00").toLocaleDateString("en-PH", {
                        weekday: "short", month: "long", day: "numeric", year: "numeric",
                      })
                    : "—",
                },
                {
                  label: "Time",
                  value:
                    cal.startHour !== null && cal.endHour !== null
                      ? `${fmtHour(cal.startHour)} – ${fmtHour(cal.endHour)}`
                      : "—",
                },
                { label: "Duration", value: `${hoursCount} hr${hoursCount !== 1 ? "s" : ""}` },
                { label: "Guests",   value: `${guests} pax` },
              ].map(({ label, value }) => (
                <div
                  key={label}
                  className="rounded-xl p-4 border"
                  style={{ background: "rgba(255,255,255,0.02)", borderColor: "var(--border)" }}
                >
                  <p className="text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>{label}</p>
                  <p className="text-white font-medium">{value}</p>
                </div>
              ))}
              {remarks && (
                <div
                  className="col-span-2 rounded-xl p-4 border"
                  style={{ background: "rgba(255,255,255,0.02)", borderColor: "var(--border)" }}
                >
                  <p className="text-xs mb-1" style={{ color: "var(--muted-foreground)" }}>Special Requests</p>
                  <p className="text-white text-sm">{remarks}</p>
                </div>
              )}
            </div>

            {/* Cost breakdown */}
            <div
              className="rounded-xl p-4 border mb-5"
              style={{ background: "rgba(124,58,237,0.08)", borderColor: "rgba(124,58,237,0.2)" }}
            >
              <div className="flex justify-between text-sm mb-2">
                <span style={{ color: "var(--muted-foreground)" }}>
                  ₱{room.pricePerHour.toLocaleString()} × {hoursCount} hr{hoursCount !== 1 ? "s" : ""}
                </span>
                <span className="text-white">₱{cost.toLocaleString()}</span>
              </div>
              <div
                className="flex justify-between items-center pt-2 border-t"
                style={{ borderColor: "rgba(124,58,237,0.2)" }}
              >
                <span className="font-bold text-white">Total</span>
                <span
                  style={{
                    fontFamily: "'Noe Display', serif",
                    fontSize: "1.5rem",
                    fontWeight: 700,
                    color: "var(--accent)",
                  }}
                >
                  ₱{cost.toLocaleString()}
                </span>
              </div>
            </div>

            {/* Booker identity card */}
            <div
              className="flex items-center gap-3 px-4 py-3 rounded-xl border mb-6"
              style={{ background: "rgba(255,255,255,0.02)", borderColor: "var(--border)" }}
            >
              <div
                className="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                style={{ background: "var(--primary)" }}
              >
                {displayName[0] ?? "?"}
              </div>
              <div className="flex-1 min-w-0">
                <p className="text-white text-sm font-medium truncate">{displayName || "Guest"}</p>
                <p className="text-xs truncate" style={{ color: "var(--muted-foreground)" }}>
                  {mode === "guest"
                    ? (guestPhone ? `📞 ${guestPhone}` : "") + (guestEmail ? ` · ${guestEmail}` : "")
                    : displayEmail}
                </p>
              </div>
              <span
                className="text-xs px-2 py-0.5 rounded-full shrink-0"
                style={{
                  background: mode === "user"
                    ? "rgba(124,58,237,0.2)"
                    : "rgba(245,158,11,0.15)",
                  color: mode === "user" ? "#c4b5fd" : "#fbbf24",
                }}
              >
                {mode === "user" ? "Member" : "Guest"}
              </span>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
              <button
                type="button"
                onClick={goBack}
                className="px-6 py-3 rounded-xl font-medium border transition-colors hover:text-white shrink-0"
                style={{ borderColor: "var(--border)", color: "var(--muted-foreground)" }}
              >
                ← Back
              </button>
              <button
                type="button"
                onClick={submit}
                className="flex-1 py-3 rounded-xl font-bold text-white transition-all hover:opacity-90 text-base"
                style={{
                  background: "var(--primary)",
                  boxShadow: "0 8px 24px rgba(124,58,237,0.35)",
                }}
              >
                🎤 Confirm Booking
              </button>
            </div>

            <p className="text-center text-xs mt-3" style={{ color: "var(--muted-foreground)" }}>
              {displayEmail !== "—"
                ? `A confirmation email will be sent to ${displayEmail}.`
                : "Booking confirmation will be available at the venue."}{" "}
              Payment is collected on-site.
            </p>
          </Section>
        )}
      </div>
    </div>
  );
}
