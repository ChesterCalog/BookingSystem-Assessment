/**
 * BookingCalendar
 *
 * Provides three views: Month | Week | Day
 * All sub-components are defined at MODULE SCOPE to prevent focus loss.
 * All hour-walk logic is guarded against NaN (no infinite loops).
 */
import { useState, useMemo, useCallback } from "react";
import {
  ALL_HOURS,
  fmtHour,
  getBookedHours,
  isDateFullyBooked,
  type Booking,
  type Room,
} from "./data";

export interface CalendarSelection {
  date: string;
  startHour: number | null;
  endHour: number | null;
}

// ── Pure helpers ─────────────────────────────────────────────────

const pad   = (n: number) => String(n).padStart(2, "0");
const toStr = (y: number, m: number, d: number) =>
  `${y}-${pad(m + 1)}-${pad(d)}`;

export const todayStr = (): string => new Date().toISOString().split("T")[0];

const WEEKDAYS_SHORT = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
const WEEKDAYS_LONG  = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
const MONTHS         = ["January","February","March","April","May","June","July","August","September","October","November","December"];

const parseDate = (s: string): Date => new Date(s + "T12:00:00");

const addDays = (date: Date, n: number): Date => {
  const d = new Date(date);
  d.setDate(d.getDate() + n);
  return d;
};

const dateStr = (d: Date): string => d.toISOString().split("T")[0];

// ── Shared sub-component props types ─────────────────────────────

interface SlotGridProps {
  date: string;
  room: Room;
  bookings: Booking[];
  startHour: number | null;
  endHour: number | null;
  onStartHour: (h: number) => void;
  onEndHour:   (h: number) => void;
}

interface CalProps {
  room: Room;
  bookings: Booking[];
  selected: string;
  viewDate: Date;
  onSelect: (d: string) => void;
  onViewDate: (d: Date) => void;
}

// ── SlotGrid ─────────────────────────────────────────────────────
// Defined at module scope → never remounted due to parent re-renders.

function SlotGrid({
  date, room, bookings, startHour, endHour, onStartHour, onEndHour,
}: SlotGridProps) {
  const bookedHours = useMemo(
    () => getBookedHours(room.id, date, bookings),
    [room.id, date, bookings],
  );

  // Build set of hours in the selected range
  const inRange = useMemo((): Set<number> => {
    if (startHour === null || endHour === null) return new Set();
    const s = new Set<number>();
    let h = startHour, iters = 0;
    while (h !== endHour && iters < 24) { s.add(h); h = (h + 1) % 24; iters++; }
    return s;
  }, [startHour, endHour]);

  const mode: "start" | "end" | "done" =
    startHour === null ? "start" : endHour === null ? "end" : "done";

  const handleSlot = useCallback((h: number) => {
    if (bookedHours.has(h)) return;
    if (mode === "start" || mode === "done") {
      onStartHour(h);
    } else {
      // Validate: end must differ from start and path must be conflict-free
      if (h === startHour) return;
      let check = startHour!, iters = 0, conflict = false;
      while (check !== h && iters < 24) {
        if (bookedHours.has(check)) { conflict = true; break; }
        check = (check + 1) % 24;
        iters++;
      }
      if (!conflict) onEndHour(h);
    }
  }, [bookedHours, mode, startHour, onStartHour, onEndHour]);

  const available = ALL_HOURS.filter(h => !bookedHours.has(h)).length;

  return (
    <div>
      {/* Status bar */}
      <div className="flex flex-wrap items-center justify-between gap-2 mb-3">
        <div className="flex items-center gap-2">
          <span className="text-xs px-3 py-1.5 rounded-full border font-medium"
            style={{ background: "rgba(124,58,237,0.1)", borderColor: "rgba(124,58,237,0.3)", color: "#c4b5fd" }}>
            {mode === "start" ? "① Click a start time"
              : mode === "end" ? "② Click an end time"
              : "✓ Range selected — click start to reset"}
          </span>
        </div>
        <span className="text-xs" style={{ color: "var(--muted-foreground)" }}>
          {available}/{ALL_HOURS.length} slots available
        </span>
      </div>

      {/* Grid */}
      <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
        {ALL_HOURS.map(h => {
          const isBooked  = bookedHours.has(h);
          const isStart   = h === startHour;
          const isEnd     = h === endHour;
          const isInRange = inRange.has(h);

          let bg     = "rgba(255,255,255,0.04)";
          let border = "rgba(255,255,255,0.1)";
          let color  = "#94a3b8";

          if (isBooked)           { bg = "rgba(239,68,68,0.1)";    border = "rgba(239,68,68,0.25)";    color = "#fca5a5"; }
          else if (isStart || isEnd) { bg = "var(--primary)";       border = "var(--primary)";          color = "#fff"; }
          else if (isInRange)     { bg = "rgba(124,58,237,0.18)";  border = "rgba(124,58,237,0.4)";    color = "#c4b5fd"; }

          return (
            <button
              key={h}
              type="button"
              disabled={isBooked}
              onClick={() => handleSlot(h)}
              className="rounded-xl py-2.5 px-1 text-xs font-medium transition-all border text-center"
              style={{
                background: bg,
                borderColor: border,
                color,
                cursor: isBooked ? "not-allowed" : "pointer",
                transform: (isStart || isEnd) ? "scale(1.05)" : undefined,
                opacity: isBooked ? 0.7 : 1,
              }}
            >
              <span className="block leading-tight">{fmtHour(h)}</span>
              <span className="block text-[9px] mt-0.5 opacity-75 leading-tight">
                {isBooked ? "Taken" : isStart ? "Start" : isEnd ? "End" : isInRange ? "•" : ""}
              </span>
            </button>
          );
        })}
      </div>

      {/* Legend */}
      <div className="flex flex-wrap gap-x-4 gap-y-1 mt-3 text-xs" style={{ color: "var(--muted-foreground)" }}>
        <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded border" style={{ background: "rgba(255,255,255,0.04)", borderColor: "rgba(255,255,255,0.1)" }} />Available</span>
        <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded" style={{ background: "var(--primary)" }} />Selected</span>
        <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded border" style={{ background: "rgba(124,58,237,0.18)", borderColor: "rgba(124,58,237,0.4)" }} />Range</span>
        <span className="flex items-center gap-1.5"><span className="w-2.5 h-2.5 rounded border" style={{ background: "rgba(239,68,68,0.1)", borderColor: "rgba(239,68,68,0.25)" }} />Booked</span>
      </div>
    </div>
  );
}

// ── Month view ───────────────────────────────────────────────────

function MonthView({ room, bookings, selected, viewDate, onSelect, onViewDate }: CalProps) {
  const today = todayStr();
  const year  = viewDate.getFullYear();
  const month = viewDate.getMonth();

  const firstDow   = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  const cells: Array<{ day: number | null; date: string }> = [];
  for (let i = 0; i < firstDow; i++) cells.push({ day: null, date: "" });
  for (let d = 1; d <= daysInMonth; d++) cells.push({ day: d, date: toStr(year, month, d) });

  const prev = () => onViewDate(new Date(year, month - 1, 1));
  const next = () => onViewDate(new Date(year, month + 1, 1));

  return (
    <div>
      {/* Nav */}
      <div className="flex items-center justify-between mb-4">
        <button type="button" onClick={prev} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">‹</button>
        <span className="font-semibold text-white">{MONTHS[month]} {year}</span>
        <button type="button" onClick={next} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">›</button>
      </div>

      {/* Day headers */}
      <div className="grid grid-cols-7 mb-1">
        {WEEKDAYS_SHORT.map(d => (
          <div key={d} className="text-center text-xs py-1 font-medium" style={{ color: "var(--muted-foreground)" }}>{d}</div>
        ))}
      </div>

      {/* Date cells */}
      <div className="grid grid-cols-7 gap-1">
        {cells.map((cell, i) => {
          if (!cell.day) return <div key={`e-${i}`} />;

          const isPast       = cell.date < today;
          const isFull       = !isPast && isDateFullyBooked(room.id, cell.date, bookings);
          const isSelected   = cell.date === selected;
          const isToday      = cell.date === today;
          const hasBookings  = !isPast && !isFull && getBookedHours(room.id, cell.date, bookings).size > 0;
          const isDisabled   = isPast || isFull;

          let bg     = "transparent";
          let clr    = isDisabled ? "rgba(148,163,184,0.3)" : "#e2e8f0";
          let border = "transparent";

          if (isSelected)                 { bg = "var(--primary)"; clr = "#fff"; }
          else if (isToday && !isDisabled) { border = "var(--primary)"; clr = "#a78bfa"; }

          return (
            <button
              key={cell.date}
              type="button"
              disabled={isDisabled}
              onClick={() => onSelect(cell.date)}
              title={isFull ? "Fully booked" : isPast ? "Past date" : undefined}
              className="relative w-full aspect-square flex items-center justify-center rounded-lg text-sm transition-all hover:bg-white/10 disabled:cursor-not-allowed"
              style={{ background: bg, color: clr, border: `1.5px solid ${border}` }}
            >
              {cell.day}
              {hasBookings && !isSelected && (
                <span className="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-amber-400" />
              )}
              {isFull && (
                <span className="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-red-500" />
              )}
            </button>
          );
        })}
      </div>

      {/* Legend */}
      <div className="flex flex-wrap gap-x-4 gap-y-1 mt-3 text-xs" style={{ color: "var(--muted-foreground)" }}>
        <span className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full bg-amber-400" />Partially booked</span>
        <span className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full bg-red-500" />Fully booked</span>
        <span className="flex items-center gap-1.5"><span className="w-2 h-2 rounded-full" style={{ background: "var(--primary)" }} />Selected</span>
      </div>
    </div>
  );
}

// ── Week view ────────────────────────────────────────────────────

function WeekView({ room, bookings, selected, viewDate, onSelect, onViewDate }: CalProps) {
  const today = todayStr();

  // Start of the week containing viewDate (Sunday)
  const weekStart = new Date(viewDate);
  weekStart.setDate(viewDate.getDate() - viewDate.getDay());

  const days = Array.from({ length: 7 }, (_, i) => {
    const d = addDays(weekStart, i);
    return { date: dateStr(d), label: WEEKDAYS_LONG[i], short: WEEKDAYS_SHORT[i], dayNum: d.getDate() };
  });

  const prev = () => onViewDate(addDays(viewDate, -7));
  const next = () => onViewDate(addDays(viewDate, 7));

  const weekLabel = `${days[0].date} – ${days[6].date}`;

  return (
    <div>
      {/* Nav */}
      <div className="flex items-center justify-between mb-4">
        <button type="button" onClick={prev} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">‹</button>
        <span className="font-semibold text-white text-sm">
          {MONTHS[parseDate(days[0].date).getMonth()]} {days[0].dayNum} –{" "}
          {MONTHS[parseDate(days[6].date).getMonth()]} {days[6].dayNum},{" "}
          {parseDate(days[6].date).getFullYear()}
        </span>
        <button type="button" onClick={next} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">›</button>
      </div>

      {/* Day columns */}
      <div className="grid grid-cols-7 gap-1.5">
        {days.map(({ date, short, dayNum }) => {
          const isPast      = date < today;
          const isFull      = !isPast && isDateFullyBooked(room.id, date, bookings);
          const isSelected  = date === selected;
          const isToday     = date === today;
          const bookedCount = getBookedHours(room.id, date, bookings).size;
          const isDisabled  = isPast || isFull;
          const pct         = Math.round((bookedCount / ALL_HOURS.length) * 100);

          return (
            <button
              key={date}
              type="button"
              disabled={isDisabled}
              onClick={() => onSelect(date)}
              className="flex flex-col items-center gap-1.5 p-2 rounded-xl border transition-all disabled:cursor-not-allowed hover:border-violet-500/50"
              style={{
                background: isSelected ? "rgba(124,58,237,0.2)" : isToday ? "rgba(124,58,237,0.06)" : "rgba(255,255,255,0.02)",
                borderColor: isSelected ? "rgba(124,58,237,0.6)" : isToday ? "rgba(124,58,237,0.3)" : "var(--border)",
                opacity: isDisabled ? 0.45 : 1,
              }}
            >
              <span className="text-xs font-medium" style={{ color: "var(--muted-foreground)" }}>{short}</span>
              <span className={`text-base font-bold ${isSelected ? "text-white" : isToday ? "text-violet-300" : isDisabled ? "text-slate-600" : "text-slate-200"}`}>
                {dayNum}
              </span>
              {/* Occupancy mini-bar */}
              <div className="w-full h-1 rounded-full overflow-hidden" style={{ background: "rgba(255,255,255,0.07)" }}>
                <div className="h-full rounded-full transition-all"
                  style={{ width: `${isFull ? 100 : pct}%`, background: isFull ? "#ef4444" : pct > 0 ? "#f59e0b" : "transparent" }} />
              </div>
              <span className="text-[9px] leading-tight" style={{ color: isFull ? "#fca5a5" : "var(--muted-foreground)" }}>
                {isFull ? "Full" : isPast ? "Past" : pct > 0 ? `${pct}%` : "Open"}
              </span>
            </button>
          );
        })}
      </div>
    </div>
  );
}

// ── Day view ─────────────────────────────────────────────────────

function DayView({ room, bookings, selected, viewDate, onSelect, onViewDate }: CalProps) {
  const today   = todayStr();
  const current = selected || dateStr(viewDate);

  const prev = () => onViewDate(addDays(viewDate, -1));
  const next = () => onViewDate(addDays(viewDate, 1));

  const bookedHours = useMemo(
    () => getBookedHours(room.id, current, bookings),
    [room.id, current, bookings],
  );

  const bookedRanges = useMemo(() => {
    return bookings
      .filter(b =>
        b.roomId === room.id &&
        b.date === current &&
        (b.status === "Pending" || b.status === "Approved"),
      )
      .map(b => ({ name: b.name, start: b.startTime, end: b.endTime, status: b.status }));
  }, [bookings, room.id, current]);

  const displayDate = parseDate(current);

  return (
    <div>
      {/* Nav */}
      <div className="flex items-center justify-between mb-4">
        <button type="button" onClick={prev} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">‹</button>
        <div className="text-center">
          <span className="font-semibold text-white">
            {WEEKDAYS_LONG[displayDate.getDay()]},{" "}
            {MONTHS[displayDate.getMonth()]} {displayDate.getDate()}, {displayDate.getFullYear()}
          </span>
          {current === today && (
            <span className="ml-2 text-xs px-2 py-0.5 rounded-full" style={{ background: "rgba(124,58,237,0.2)", color: "#a78bfa" }}>Today</span>
          )}
        </div>
        <button type="button" onClick={next} className="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-white/10 text-slate-400 hover:text-white text-lg">›</button>
      </div>

      {/* Timeline */}
      <div className="space-y-1.5 max-h-64 overflow-y-auto pr-1">
        {ALL_HOURS.map(h => {
          const isBooked = bookedHours.has(h);
          const range    = bookedRanges.find(r => {
            const sh = parseInt(r.start.split(":")[0], 10);
            const eh = parseInt(r.end.split(":")[0], 10);
            let cur = sh, iters = 0;
            while (cur !== eh && iters < 24) { if (cur === h) return true; cur = (cur + 1) % 24; iters++; }
            return false;
          });

          return (
            <div key={h} className="flex items-center gap-3">
              <span className="w-16 shrink-0 text-xs text-right" style={{ color: "var(--muted-foreground)" }}>{fmtHour(h)}</span>
              <div className="flex-1 h-8 rounded-lg flex items-center px-3 text-xs font-medium border transition-all"
                style={{
                  background: isBooked ? "rgba(239,68,68,0.1)" : "rgba(255,255,255,0.03)",
                  borderColor: isBooked ? "rgba(239,68,68,0.25)" : "rgba(255,255,255,0.08)",
                  color: isBooked ? "#fca5a5" : "#64748b",
                }}>
                {isBooked && range
                  ? <span>{range.name} <span className="opacity-60">({range.status})</span></span>
                  : isBooked ? "Reserved" : <span className="text-emerald-600">Available</span>
                }
              </div>
            </div>
          );
        })}
      </div>

      {/* Select this day CTA */}
      {current >= today && !isDateFullyBooked(room.id, current, bookings) && current !== selected && (
        <button type="button" onClick={() => onSelect(current)}
          className="w-full mt-3 py-2.5 rounded-xl text-sm font-semibold text-white transition-all hover:opacity-90 border"
          style={{ background: "rgba(124,58,237,0.15)", borderColor: "rgba(124,58,237,0.35)" }}>
          Select this day →
        </button>
      )}
    </div>
  );
}

// ── Main exported component ───────────────────────────────────────

interface Props {
  room: Room;
  existingBookings: Booking[];
  value: CalendarSelection;
  onChange: (v: CalendarSelection) => void;
}

type ViewMode = "month" | "week" | "day";

export function BookingCalendar({ room, existingBookings, value, onChange }: Props) {
  const [viewMode, setViewMode] = useState<ViewMode>("month");
  const [viewDate, setViewDate] = useState<Date>(() => new Date());

  const setDate = useCallback((date: string) => {
    // When a new date is picked, reset time selection
    onChange({ date, startHour: null, endHour: null });
    // In week/day mode jump to that date's week/day
    setViewDate(parseDate(date));
  }, [onChange]);

  const setStart = useCallback((h: number) => {
    onChange({ ...value, startHour: h, endHour: null });
  }, [onChange, value]);

  const setEnd = useCallback((h: number) => {
    onChange({ ...value, endHour: h });
  }, [onChange, value]);

  const clearTime = useCallback(() => {
    onChange({ ...value, startHour: null, endHour: null });
  }, [onChange, value]);

  const calProps: CalProps = {
    room,
    bookings: existingBookings,
    selected: value.date,
    viewDate,
    onSelect: setDate,
    onViewDate: setViewDate,
  };

  return (
    <div className="space-y-5">
      {/* ── Calendar card ── */}
      <div className="rounded-2xl p-5 border" style={{ background: "rgba(255,255,255,0.02)", borderColor: "var(--border)" }}>

        {/* Header row */}
        <div className="flex items-center justify-between mb-4">
          <h3 className="font-semibold text-white text-sm flex items-center gap-2">
            📅 Select Date
            {value.date && (
              <span className="ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium" style={{ background: "rgba(124,58,237,0.2)", color: "#c4b5fd" }}>
                {parseDate(value.date).toLocaleDateString("en-PH", { weekday: "short", month: "short", day: "numeric" })}
              </span>
            )}
          </h3>

          {/* View toggle */}
          <div className="flex rounded-lg overflow-hidden border" style={{ borderColor: "var(--border)" }}>
            {(["month","week","day"] as ViewMode[]).map(v => (
              <button
                key={v}
                type="button"
                onClick={() => setViewMode(v)}
                className="px-3 py-1 text-xs font-medium capitalize transition-colors"
                style={{
                  background: viewMode === v ? "var(--primary)" : "rgba(255,255,255,0.04)",
                  color: viewMode === v ? "#fff" : "var(--muted-foreground)",
                }}
              >
                {v}
              </button>
            ))}
          </div>
        </div>

        {viewMode === "month" && <MonthView {...calProps} />}
        {viewMode === "week"  && <WeekView  {...calProps} />}
        {viewMode === "day"   && <DayView   {...calProps} />}
      </div>

      {/* ── Time slots card — only visible after a date is chosen ── */}
      {value.date && (
        <div className="rounded-2xl p-5 border" style={{ background: "rgba(255,255,255,0.02)", borderColor: "var(--border)" }}>
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-semibold text-white text-sm flex items-center gap-2">
              🕐 Select Time Slot
              {value.startHour !== null && value.endHour !== null && (
                <span className="ml-1 px-2.5 py-0.5 rounded-full text-xs" style={{ background: "rgba(124,58,237,0.2)", color: "#c4b5fd" }}>
                  {`${fmtHour(value.startHour)} – ${fmtHour(value.endHour)}`}
                </span>
              )}
            </h3>
            {(value.startHour !== null || value.endHour !== null) && (
              <button type="button" onClick={clearTime} className="text-xs transition-colors hover:text-white" style={{ color: "var(--muted-foreground)" }}>
                ↺ Reset
              </button>
            )}
          </div>

          <SlotGrid
            date={value.date}
            room={room}
            bookings={existingBookings}
            startHour={value.startHour}
            endHour={value.endHour}
            onStartHour={setStart}
            onEndHour={setEnd}
          />
        </div>
      )}
    </div>
  );
}
