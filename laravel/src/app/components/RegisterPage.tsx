/**
 * RegisterPage
 *
 * Focus-loss fix:
 *  - FIELDS array at module scope (not inside render).
 *  - One stable useCallback per input field — not a shared generic handler
 *    that would need to recreate a closure on every render.
 *  - No components defined inside this component.
 */
import { useState, useCallback } from "react";
import { type Page, type AppUser } from "../App";

interface Props { navigate: (p: Page) => void; login: (u: AppUser) => void; }

// Module-scope constants — never recreated
const INPUT_CLS  = "w-full rounded-xl px-4 py-3 text-white text-sm border focus:outline-none placeholder:text-slate-600";
const INPUT_STYLE: React.CSSProperties = { background: "var(--input-background)", borderColor: "var(--border)" };

const strengthLabel = (pw: string): { label: string; color: string; width: string } => {
  const len = pw.length;
  if (len === 0)  return { label: "",         color: "transparent",  width: "0%" };
  if (len < 6)    return { label: "Weak",     color: "#ef4444",      width: "25%" };
  if (len < 8)    return { label: "Fair",     color: "#f59e0b",      width: "50%" };
  if (len < 12)   return { label: "Good",     color: "#22c55e",      width: "75%" };
  return              { label: "Strong",   color: "#10b981",      width: "100%" };
};

export function RegisterPage({ navigate, login }: Props) {
  const [name,     setName]     = useState("");
  const [email,    setEmail]    = useState("");
  const [phone,    setPhone]    = useState("");
  const [password, setPassword] = useState("");
  const [confirm,  setConfirm]  = useState("");
  const [showPw,   setShowPw]   = useState(false);
  const [errors,   setErrors]   = useState<Record<string, string>>({});
  const [loading,  setLoading]  = useState(false);

  // One stable callback per field — prevents focus loss
  const handleName     = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setName(e.target.value);     setErrors(p => ({ ...p, name:     "" })); }, []);
  const handleEmail    = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setEmail(e.target.value);    setErrors(p => ({ ...p, email:    "" })); }, []);
  const handlePhone    = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setPhone(e.target.value); }, []);
  const handlePassword = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setPassword(e.target.value); setErrors(p => ({ ...p, password: "" })); }, []);
  const handleConfirm  = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setConfirm(e.target.value);  setErrors(p => ({ ...p, confirm:  "" })); }, []);
  const togglePw       = useCallback(() => setShowPw(v => !v), []);

  const validate = (): boolean => {
    const e: Record<string, string> = {};
    if (!name.trim())                                          e.name     = "Full name is required.";
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/))           e.email    = "A valid email address is required.";
    if (password.length < 8)                                   e.password = "Password must be at least 8 characters.";
    if (password !== confirm)                                  e.confirm  = "Passwords do not match.";
    setErrors(e);
    return Object.keys(e).length === 0;
  };

  const submit = useCallback(async (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate()) return;
    setLoading(true);
    await new Promise(r => setTimeout(r, 700));
    setLoading(false);
    login({ name: name.trim(), email: email.trim(), role: "user" });
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [name, email, phone, password, confirm, login]);

  const strength = strengthLabel(password);

  return (
    <div
      className="min-h-screen pt-16 flex items-center justify-center px-4"
      style={{ background: "radial-gradient(ellipse at 40% 60%, rgba(124,58,237,0.15) 0%, transparent 60%), var(--background)" }}
    >
      <div className="w-full max-w-md py-8">
        <div className="rounded-2xl p-8 border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>

          {/* Header */}
          <div className="text-center mb-8">
            <div className="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4"
              style={{ background: "rgba(124,58,237,0.15)" }}>
              🎤
            </div>
            <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.75rem", fontWeight: 700, color: "#fff" }}>
              Join KaraokeZone
            </h1>
            <p className="text-sm mt-1" style={{ color: "var(--muted-foreground)" }}>
              Create a free account to manage your bookings and view your history.
            </p>
          </div>

          <form onSubmit={submit} className="space-y-4" noValidate>

            {/* Full Name */}
            <div>
              <label htmlFor="reg-name" className="block text-sm font-medium text-white mb-2">
                Full Name <span className="text-red-400">*</span>
              </label>
              <input id="reg-name" type="text" value={name} onChange={handleName}
                placeholder="Juan dela Cruz" autoComplete="name"
                className={INPUT_CLS} style={INPUT_STYLE} />
              {errors.name && <p className="text-red-400 text-xs mt-1">{errors.name}</p>}
            </div>

            {/* Email */}
            <div>
              <label htmlFor="reg-email" className="block text-sm font-medium text-white mb-2">
                Email Address <span className="text-red-400">*</span>
              </label>
              <input id="reg-email" type="email" value={email} onChange={handleEmail}
                placeholder="you@example.com" autoComplete="email"
                className={INPUT_CLS} style={INPUT_STYLE} />
              {errors.email && <p className="text-red-400 text-xs mt-1">{errors.email}</p>}
            </div>

            {/* Phone (optional) */}
            <div>
              <label htmlFor="reg-phone" className="block text-sm font-medium text-white mb-2">
                Phone Number
                <span className="font-normal text-xs ml-1" style={{ color: "var(--muted-foreground)" }}>(optional)</span>
              </label>
              <input id="reg-phone" type="tel" value={phone} onChange={handlePhone}
                placeholder="+63 912 345 6789" autoComplete="tel"
                className={INPUT_CLS} style={INPUT_STYLE} />
            </div>

            {/* Password */}
            <div>
              <label htmlFor="reg-password" className="block text-sm font-medium text-white mb-2">
                Password <span className="text-red-400">*</span>
              </label>
              <div className="relative">
                <input id="reg-password"
                  type={showPw ? "text" : "password"}
                  value={password}
                  onChange={handlePassword}
                  placeholder="At least 8 characters"
                  autoComplete="new-password"
                  className={INPUT_CLS}
                  style={{ ...INPUT_STYLE, paddingRight: "4.5rem" }}
                />
                <button type="button" onClick={togglePw} tabIndex={-1}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-xs transition-colors text-slate-500 hover:text-slate-300">
                  {showPw ? "Hide" : "Show"}
                </button>
              </div>
              {errors.password && <p className="text-red-400 text-xs mt-1">{errors.password}</p>}

              {/* Strength bar */}
              {password.length > 0 && (
                <div className="mt-2 flex items-center gap-2">
                  <div className="flex-1 h-1.5 rounded-full overflow-hidden" style={{ background: "rgba(255,255,255,0.08)" }}>
                    <div className="h-full rounded-full transition-all" style={{ width: strength.width, background: strength.color }} />
                  </div>
                  <span className="text-xs shrink-0" style={{ color: strength.color }}>{strength.label}</span>
                </div>
              )}
            </div>

            {/* Confirm Password */}
            <div>
              <label htmlFor="reg-confirm" className="block text-sm font-medium text-white mb-2">
                Confirm Password <span className="text-red-400">*</span>
              </label>
              <input id="reg-confirm"
                type={showPw ? "text" : "password"}
                value={confirm}
                onChange={handleConfirm}
                placeholder="Re-enter your password"
                autoComplete="new-password"
                className={INPUT_CLS}
                style={INPUT_STYLE}
              />
              {errors.confirm && <p className="text-red-400 text-xs mt-1">{errors.confirm}</p>}

              {/* Match indicator */}
              {confirm.length > 0 && (
                <p className="text-xs mt-1" style={{ color: confirm === password ? "#34d399" : "#fca5a5" }}>
                  {confirm === password ? "✓ Passwords match" : "✗ Passwords do not match"}
                </p>
              )}
            </div>

            {/* Submit */}
            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2 mt-2"
              style={{ background: "var(--primary)", boxShadow: "0 6px 20px rgba(124,58,237,0.3)" }}
            >
              {loading ? (
                <>
                  <span className="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin" />
                  Creating account…
                </>
              ) : "Create Account"}
            </button>
          </form>

          <p className="text-center text-sm mt-6" style={{ color: "var(--muted-foreground)" }}>
            Already have an account?{" "}
            <button type="button" onClick={() => navigate("login")} className="text-violet-400 hover:text-white transition-colors font-medium">
              Sign in
            </button>
          </p>
        </div>
      </div>
    </div>
  );
}
