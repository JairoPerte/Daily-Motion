import { differenceInMinutes, parseISO, subHours } from "date-fns";

export function formatToDate(phpDate: string): string {
  const normalized = phpDate.replace(" ", "T");

  // Crea el objeto Date interpretando el string como UTC
  const date = new Date(normalized);

  if (isNaN(date.getTime())) return "Fecha inválida";

  date.setTime(date.getTime() - 2 * 60 * 60 * 1000);

  return date.toLocaleDateString("es-ES", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

export function getActivityMinutes(start: string, end: string): number {
  const startDate = subHours(parseISO(start), 2);
  const endDate = subHours(parseISO(end), 2);
  const minutes = differenceInMinutes(endDate, startDate);
  return Math.max(Math.ceil(minutes), 1); // Al menos 1 minuto
}

export function formatTime(seconds: number): string {
  const m = Math.floor(seconds / 60)
    .toString()
    .padStart(1, "0");
  const s = (seconds % 60).toString().padStart(2, "0");
  return `${m}:${s}`;
}

export function getLocalISOWithTimezone(date: Date): string {
  const tzOffsetMinutes = date.getTimezoneOffset();
  const offsetHours = Math.floor(Math.abs(tzOffsetMinutes) / 60)
    .toString()
    .padStart(2, "0");
  const offsetMinutes = (Math.abs(tzOffsetMinutes) % 60)
    .toString()
    .padStart(2, "0");
  const sign = tzOffsetMinutes > 0 ? "-" : "+";

  // Ajustar la fecha al formato ISO Local que es la que usa la API
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const day = date.getDate().toString().padStart(2, "0");
  const hours = date.getHours().toString().padStart(2, "0");
  const minutes = date.getMinutes().toString().padStart(2, "0");
  const seconds = date.getSeconds().toString().padStart(2, "0");

  const localISO = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
  return `${localISO}${sign}${offsetHours}:${offsetMinutes}`;
}
