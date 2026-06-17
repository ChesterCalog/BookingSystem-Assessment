export interface Room {
  id: number;
  name: string;
  type: "Standard" | "Deluxe" | "VIP" | "Party";
  size: "Small" | "Medium" | "Large" | "XL";
  capacity: number;
  pricePerHour: number;
  description: string;
  amenities: string[];
  image: string;
  available: boolean;
}

export interface Booking {
  id: number;
  refNumber: string;
  type: "member" | "guest";
  name: string;
  email: string;
  phone: string;
  room: string;
  roomId: number;
  date: string;        // "YYYY-MM-DD"
  startTime: string;   // "HH:00"
  endTime: string;     // "HH:00"
  guests: number;
  totalCost: number;
  status: "Pending" | "Approved" | "Rejected" | "Completed" | "Cancelled";
}

// ── Rooms ────────────────────────────────────────────────────────
export const ROOMS: Room[] = [
  {
    id: 1,
    name: "The Starlight Booth",
    type: "Standard",
    size: "Small",
    capacity: 4,
    pricePerHour: 200,
    description: "A cozy intimate booth perfect for couples or close friends. Features warm lighting and a curated song catalog.",
    amenities: ["2 Microphones", "HD TV Screen", "Air Conditioning", "Song Book", "WiFi"],
    image: "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
  {
    id: 2,
    name: "The Neon Stage",
    type: "Standard",
    size: "Medium",
    capacity: 8,
    pricePerHour: 350,
    description: "A vibrant medium-sized room with neon aesthetics. Great for barkada nights and casual hangouts.",
    amenities: ["4 Microphones", "HD TV Screen", "Surround Sound", "Air Conditioning", "Sofa Seating", "WiFi"],
    image: "https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
  {
    id: 3,
    name: "The Deluxe Den",
    type: "Deluxe",
    size: "Medium",
    capacity: 10,
    pricePerHour: 500,
    description: "Upgraded experience with premium audio, tambourine set, and a mini refreshment bar.",
    amenities: ["4 Microphones", "4K TV", "Surround Sound", "Air Conditioning", "Sofa Seating", "Mini Bar", "Tambourine", "WiFi"],
    image: "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
  {
    id: 4,
    name: "The Golden Mic Room",
    type: "Deluxe",
    size: "Large",
    capacity: 15,
    pricePerHour: 750,
    description: "Our flagship deluxe room. Perfect for medium-sized celebrations with premium amenities.",
    amenities: ["6 Microphones", "4K TV", "Surround Sound", "AC", "Sofa Seating", "Mini Bar", "Party Lights", "Tambourine", "WiFi"],
    image: "https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
  {
    id: 5,
    name: "The VIP Lounge",
    type: "VIP",
    size: "Large",
    capacity: 18,
    pricePerHour: 1200,
    description: "The ultimate VIP experience. Luxury furnishings, private bathroom, dedicated attendant, and studio-grade audio.",
    amenities: ["8 Microphones", "75\" 4K Screen", "Studio Sound", "Private Bathroom", "Mini Bar", "Party Lights", "Disco Ball", "WiFi"],
    image: "https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
  {
    id: 6,
    name: "The Grand Arena",
    type: "Party",
    size: "XL",
    capacity: 30,
    pricePerHour: 2000,
    description: "Our largest room — the ultimate party hall with concert-grade setup and a dedicated dance floor.",
    amenities: ["10 Microphones", "85\" 4K Screen", "Concert Sound", "Private Bathroom", "Full Bar", "Stage", "Dance Floor", "Disco Ball", "WiFi"],
    image: "https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=600&h=400&fit=crop&auto=format",
    available: true,
  },
];

// ── Seed bookings relative to today ─────────────────────────────
const d = (daysFromNow: number): string => {
  const dt = new Date();
  dt.setDate(dt.getDate() + daysFromNow);
  return dt.toISOString().split("T")[0];
};

export const SAMPLE_BOOKINGS: Booking[] = [
  { id: 1,  refNumber: "BK-A1B2C3D4", type: "member", name: "Maria Santos",   email: "maria@example.com",    phone: "+63 912 111 1111", room: "The VIP Lounge",       roomId: 5, date: d(2), startTime: "18:00", endTime: "21:00", guests: 10, totalCost: 3600,  status: "Approved"  },
  { id: 2,  refNumber: "GB-E5F6G7H8", type: "guest",  name: "Patricia Lim",   email: "patricia@example.com", phone: "+63 917 555 1001", room: "The Neon Stage",       roomId: 2, date: d(2), startTime: "14:00", endTime: "17:00", guests: 5,  totalCost: 1050,  status: "Pending"   },
  { id: 3,  refNumber: "BK-I9J0K1L2", type: "member", name: "James Reyes",    email: "james@example.com",    phone: "+63 912 222 2222", room: "The Deluxe Den",       roomId: 3, date: d(3), startTime: "14:00", endTime: "17:00", guests: 8,  totalCost: 1500,  status: "Pending"   },
  { id: 4,  refNumber: "GB-M3N4O5P6", type: "guest",  name: "Roberto Garcia", email: "roberto@example.com",  phone: "+63 917 555 2002", room: "The Golden Mic Room",  roomId: 4, date: d(3), startTime: "19:00", endTime: "22:00", guests: 12, totalCost: 2250,  status: "Approved"  },
  { id: 5,  refNumber: "BK-Q7R8S9T0", type: "member", name: "Ana Cruz",       email: "ana@example.com",      phone: "+63 912 333 3333", room: "The Grand Arena",      roomId: 6, date: d(5), startTime: "17:00", endTime: "23:00", guests: 25, totalCost: 12000, status: "Pending"   },
  { id: 6,  refNumber: "GB-U1V2W3X4", type: "guest",  name: "Sophia Tan",     email: "sophia@example.com",   phone: "+63 917 555 3003", room: "The VIP Lounge",       roomId: 5, date: d(7), startTime: "20:00", endTime: "23:00", guests: 15, totalCost: 3600,  status: "Approved"  },
  { id: 7,  refNumber: "BK-Y5Z6A7B8", type: "member", name: "Carlo Mendoza",  email: "carlo@example.com",    phone: "+63 912 444 4444", room: "The Starlight Booth",  roomId: 1, date: d(4), startTime: "19:00", endTime: "21:00", guests: 4,  totalCost: 400,   status: "Completed" },
  { id: 8,  refNumber: "BK-C9D0E1F2", type: "member", name: "Lena Reyes",     email: "lena@example.com",     phone: "+63 912 555 5555", room: "The Neon Stage",       roomId: 2, date: d(2), startTime: "18:00", endTime: "21:00", guests: 6,  totalCost: 1050,  status: "Approved"  },
  { id: 9,  refNumber: "GB-G3H4I5J6", type: "guest",  name: "Marco Villena",  email: "marco@example.com",    phone: "+63 917 666 6666", room: "The Deluxe Den",       roomId: 3, date: d(5), startTime: "14:00", endTime: "18:00", guests: 9,  totalCost: 2000,  status: "Pending"   },
  { id: 10, refNumber: "BK-K7L8M9N0", type: "member", name: "Rina Castillo",  email: "rina@example.com",     phone: "+63 912 777 7777", room: "The Golden Mic Room",  roomId: 4, date: d(6), startTime: "16:00", endTime: "20:00", guests: 13, totalCost: 3000,  status: "Approved"  },
];

// ── Bookable hours: 10 AM → 2 AM ─────────────────────────────────
export const ALL_HOURS = [10,11,12,13,14,15,16,17,18,19,20,21,22,23,0,1,2];

/** Display label for an integer hour, e.g. 14 → "2:00 PM" */
export const fmtHour = (h: number): string => {
  if (!Number.isInteger(h)) return "—";
  const suffix  = h < 12 || h === 0 ? "AM" : "PM";
  const display = h === 0 ? 12 : h > 12 ? h - 12 : h;
  return `${display}:00 ${suffix}`;
};

/** "HH:00" string → safe integer hour (returns -1 on invalid input) */
export const timeToHour = (t: string): number => {
  if (typeof t !== "string") return -1;
  const parts = t.split(":");
  if (parts.length < 1) return -1;
  const h = parseInt(parts[0], 10);
  return Number.isInteger(h) && h >= 0 && h <= 23 ? h : -1;
};

/**
 * Walk from startHour up to (not including) endHour.
 * SAFE: capped at 24 iterations, returns [] on invalid input.
 */
const walkHours = (startHour: number, endHour: number): number[] => {
  if (!Number.isInteger(startHour) || !Number.isInteger(endHour)) return [];
  if (startHour === endHour) return [];
  const hours: number[] = [];
  let h = ((startHour % 24) + 24) % 24;
  const end = ((endHour % 24) + 24) % 24;
  let iters = 0;
  while (h !== end && iters < 24) {
    hours.push(h);
    h = (h + 1) % 24;
    iters++;
  }
  return hours;
};

/**
 * Returns a Set of integer hours that are occupied for a given
 * room on a given date (only Pending + Approved bookings count).
 */
export const getBookedHours = (
  roomId: number,
  date: string,
  existingBookings: Booking[],
): Set<number> => {
  const booked = new Set<number>();
  if (!date) return booked;

  existingBookings
    .filter(b =>
      b.roomId === roomId &&
      b.date === date &&
      (b.status === "Pending" || b.status === "Approved"),
    )
    .forEach(b => {
      const sh = timeToHour(b.startTime);
      const eh = timeToHour(b.endTime);
      if (sh === -1 || eh === -1) return; // skip corrupt data
      walkHours(sh, eh).forEach(h => booked.add(h));
    });

  return booked;
};

/**
 * True if the proposed [startHour, endHour) window conflicts with
 * any existing booking for that room + date.
 * Returns false on invalid input (fail-open to avoid blocking the user).
 */
export const hasConflict = (
  roomId: number,
  date: string,
  startHour: number,
  endHour: number,
  existingBookings: Booking[],
): boolean => {
  if (!Number.isInteger(startHour) || !Number.isInteger(endHour)) return false;
  if (startHour === endHour) return false;
  const booked = getBookedHours(roomId, date, existingBookings);
  return walkHours(startHour, endHour).some(h => booked.has(h));
};

/**
 * True if every bookable slot on a date is taken.
 */
export const isDateFullyBooked = (
  roomId: number,
  date: string,
  bookings: Booking[],
): boolean => {
  if (!date) return false;
  const booked = getBookedHours(roomId, date, bookings);
  return ALL_HOURS.every(h => booked.has(h));
};

/**
 * Calculate total cost for a session.
 * SAFE: guards against NaN / equal hours / invalid input.
 */
export const calcCost = (
  pricePerHour: number,
  startHour: number,
  endHour: number,
): number => {
  if (
    !Number.isFinite(pricePerHour) ||
    !Number.isInteger(startHour)   ||
    !Number.isInteger(endHour)     ||
    startHour === endHour
  ) return 0;
  return walkHours(startHour, endHour).length * pricePerHour;
};

export const statusColor = (status: string): string => {
  switch (status) {
    case "Approved":  return "bg-emerald-500/15 text-emerald-400 border border-emerald-500/25";
    case "Pending":   return "bg-amber-500/15 text-amber-400 border border-amber-500/25";
    case "Rejected":  return "bg-red-500/15 text-red-400 border border-red-500/25";
    case "Completed": return "bg-blue-500/15 text-blue-400 border border-blue-500/25";
    case "Cancelled": return "bg-slate-500/15 text-slate-400 border border-slate-500/25";
    default:          return "bg-slate-500/15 text-slate-400";
  }
};
