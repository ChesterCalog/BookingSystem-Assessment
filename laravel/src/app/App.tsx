import { useState } from "react";
import { LandingPage } from "./components/LandingPage";
import { RoomsPage } from "./components/RoomsPage";
import { RoomDetailPage } from "./components/RoomDetailPage";
import { BookingPage } from "./components/BookingPage";
import { GuestBookingPage } from "./components/GuestBookingPage";
import { LoginPage } from "./components/LoginPage";
import { RegisterPage } from "./components/RegisterPage";
import { ProfilePage } from "./components/ProfilePage";
import { AdminDashboard } from "./components/AdminDashboard";
import { AdminRooms } from "./components/AdminRooms";
import { AdminBookings } from "./components/AdminBookings";
import { ConfirmationPage } from "./components/ConfirmationPage";
import { Navbar } from "./components/Navbar";

export type Page =
  | "home" | "rooms" | "room-detail" | "book" | "guest-book"
  | "login" | "register" | "profile" | "admin" | "admin-rooms"
  | "admin-bookings" | "confirmation";

export interface AppUser {
  name: string;
  email: string;
  role: "admin" | "user";
}

export default function App() {
  const [page, setPage] = useState<Page>("home");
  const [selectedRoomId, setSelectedRoomId] = useState<number>(1);
  const [user, setUser] = useState<AppUser | null>(null);
  const [confirmationRef, setConfirmationRef] = useState("");

  const navigate = (p: Page, roomId?: number) => {
    setPage(p);
    if (roomId !== undefined) setSelectedRoomId(roomId);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const login = (u: AppUser) => {
    setUser(u);
    navigate(u.role === "admin" ? "admin" : "home");
  };

  const logout = () => { setUser(null); navigate("home"); };

  const confirmBooking = (ref: string) => {
    setConfirmationRef(ref);
    navigate("confirmation");
  };

  const isAdmin = page === "admin" || page === "admin-rooms" || page === "admin-bookings";

  return (
    <div className="min-h-screen" style={{ background: "var(--background)", color: "var(--foreground)", fontFamily: "'DM Sans', sans-serif" }}>
      {!isAdmin && <Navbar page={page} user={user} navigate={navigate} logout={logout} />}

      {page === "home"         && <LandingPage navigate={navigate} />}
      {page === "rooms"        && <RoomsPage navigate={navigate} />}
      {page === "room-detail"  && <RoomDetailPage roomId={selectedRoomId} navigate={navigate} user={user} />}
      {page === "book"         && <BookingPage navigate={navigate} user={user} selectedRoomId={selectedRoomId} confirmBooking={confirmBooking} />}
      {page === "guest-book"   && <GuestBookingPage navigate={navigate} user={user} selectedRoomId={selectedRoomId} confirmBooking={confirmBooking} />}
      {page === "login"        && <LoginPage navigate={navigate} login={login} />}
      {page === "register"     && <RegisterPage navigate={navigate} login={login} />}
      {page === "profile"      && <ProfilePage navigate={navigate} user={user} />}
      {page === "confirmation" && <ConfirmationPage navigate={navigate} refNumber={confirmationRef} />}
      {page === "admin"        && <AdminDashboard navigate={navigate} user={user} logout={logout} />}
      {page === "admin-rooms"  && <AdminRooms navigate={navigate} user={user} logout={logout} />}
      {page === "admin-bookings" && <AdminBookings navigate={navigate} user={user} logout={logout} />}
    </div>
  );
}
