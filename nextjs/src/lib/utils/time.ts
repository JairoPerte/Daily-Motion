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
