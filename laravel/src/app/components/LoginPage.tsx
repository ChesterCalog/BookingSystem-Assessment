/**
 * LoginPage
 *
 * Focus-loss fix:
 *  - No component definitions inside this component.
 *  - onChange handlers reference stable field-specific callbacks,
 *    not inline arrow functions that get recreated every render.
 */
import { useState, useCallback } from "react";
import { type Page, type AppUser } from "../App";

interface Props {
  navigate: (p: Page) => void;
  login: (u: AppUser) => void;
}

const INPUT_CLS  = "w-full rounded-xl px-4 py-3 text-white text-sm border focus:outline-none placeholder:text-slate-600";
const INPUT_STYLE: React.CSSProperties = { background: "var(--input-background)", borderColor: "var(--border)" };

export function LoginPage({ navigate, login }: Props) {
  const [email,    setEmail]    = useState("");
  const [password, setPassword] = useState("");
  const [showPw,   setShowPw]   = useState(false);
  const [error,    setError]    = useState("");
  const [loading,  setLoading]  = useState(false);

  // Stable change handlers — no inline arrow functions on the JSX
  const handleEmail    = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setEmail(e.target.value);    setError(""); }, []);
  const handlePassword = useCallback((e: React.ChangeEvent<HTMLInputElement>) => { setPassword(e.target.value); setError(""); }, []);
  const togglePw       = useCallback(() => setShowPw(v => !v), []);

  const submit = useCallback(async (e: React.FormEvent) => {
    e.preventDefault();
    if (!email.trim())  { setError("Please enter your email address.");  return; }
    if (!password)      { setError("Please enter your password.");        return; }
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
      setError("Please enter a valid email address.");
      return;
    }

    setLoading(true);
    // Simulate async auth — replace with real API call
    await new Promise(r => setTimeout(r, 700));
    setLoading(false);

    // No hardcoded accounts — inform user to sign up if not found
    setError("No account found with these credentials. Please check your email and password, or sign up for a new account.");
  }, [email, password]);

  return (
    <div
      className="min-h-screen pt-16 flex items-center justify-center px-4"
      style={{ background: "radial-gradient(ellipse at 60% 40%, rgba(124,58,237,0.15) 0%, transparent 60%), var(--background)" }}
    >
      <div className="w-full max-w-md">
        <div className="rounded-2xl p-8 border" style={{ background: "var(--card)", borderColor: "var(--border)" }}>

          {/* Header */}
          <div className="text-center mb-8">
            <div className="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4"
              style={{ background: "rgba(124,58,237,0.15)" }}>
              🎤
            </div>
            <h1 style={{ fontFamily: "'Noe Display', serif", fontSize: "1.75rem", fontWeight: 700, color: "#fff" }}>
              Welcome Back
            </h1>
            <p className="text-sm mt-1" style={{ color: "var(--muted-foreground)" }}>
              Sign in to manage your bookings
            </p>
          </div>

          {/* Error banner */}
          {error && (
            <div className="mb-5 px-4 py-3 rounded-xl text-sm flex items-start gap-2 border"
              style={{ background: "rgba(239,68,68,0.08)", borderColor: "rgba(239,68,68,0.25)", color: "#fca5a5" }}>
              <span className="shrink-0 mt-0.5">⚠️</span>
              <span>{error}</span>
            </div>
          )}

          <form onSubmit={submit} className="space-y-4" noValidate>

            {/* Email */}
            <div>
              <label htmlFor="login-email" className="block text-sm font-medium text-white mb-2">
                Email Address
              </label>
              <input
                id="login-email"
                type="email"
                value={email}
                onChange={handleEmail}
                placeholder="you@example.com"
                autoComplete="email"
                className={INPUT_CLS}
                style={INPUT_STYLE}
              />
            </div>

            {/* Password */}
            <div>
              <label htmlFor="login-password" className="block text-sm font-medium text-white mb-2">
                Password
              </label>
              <div className="relative">
                <input
                  id="login-password"
                  type={showPw ? "text" : "password"}
                  value={password}
                  onChange={handlePassword}
                  placeholder="Enter your password"
                  autoComplete="current-password"
                  className={INPUT_CLS}
                  style={{ ...INPUT_STYLE, paddingRight: "4.5rem" }}
                />
                <button
                  type="button"
                  onClick={togglePw}
                  tabIndex={-1}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-xs transition-colors text-slate-500 hover:text-slate-300"
                >
                  {showPw ? "Hide" : "Show"}
                </button>
              </div>
            </div>

            {/* Submit */}
            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              style={{ background: "var(--primary)", boxShadow: "0 6px 20px rgba(124,58,237,0.3)" }}
            >
              {loading ? (
                <>
                  <span className="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin" />
                  Signing in…
                </>
              ) : "Sign In"}
            </button>
          </form>

          {/* Footer */}
          <div className="mt-6 space-y-2 text-center text-sm" style={{ color: "var(--muted-foreground)" }}>
            <p>
              Don't have an account?{" "}
              <button type="button" onClick={() => navigate("register")} className="text-violet-400 hover:text-white transition-colors font-medium">
                Create one
              </button>
            </p>
            <p>
              Or{" "}
              <button type="button" onClick={() => navigate("guest-book")} className="text-amber-400 hover:text-white transition-colors">
                continue as a guest
              </button>
              {" "}without an account
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
