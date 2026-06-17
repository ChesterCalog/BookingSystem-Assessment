/**
 * GuestBookingPage
 *
 * Thin forwarder to the unified BookingPage, pre-selecting "guest" mode.
 * All booking logic lives in BookingPage to avoid code duplication.
 */
import { type Page, type AppUser } from "../App";
import { BookingPage } from "./BookingPage";

interface Props {
  navigate: (p: Page, id?: number) => void;
  user: AppUser | null;
  selectedRoomId: number;
  confirmBooking: (ref: string) => void;
}

export function GuestBookingPage({ navigate, user, selectedRoomId, confirmBooking }: Props) {
  return (
    <BookingPage
      navigate={navigate}
      user={user}
      selectedRoomId={selectedRoomId}
      confirmBooking={confirmBooking}
      defaultMode="guest"
    />
  );
}
