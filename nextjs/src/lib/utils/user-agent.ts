export function getDevice(userAgent: string): 0 | 1 | 2 {
  const ua = userAgent.toLowerCase();
  if (/mobile|android|iphone/.test(ua)) return 1;
  if (/windows|macintosh|linux/.test(ua)) return 2;
  return 0;
}

export function getDeviceInfo(userAgent: string): string {
  const ua = userAgent.toLowerCase();
  const osMatch = /windows|macintosh|linux|android|iphone/.exec(ua);
  const browserMatch = /chrome|firefox|safari|edg/.exec(ua);

  const os = osMatch?.[0];
  const browser = browserMatch?.[0];

  if (!os && !browser) return "Dispositivo desconocido";

  const displayOS = os
    ? os === "macintosh"
      ? "Mac"
      : os.charAt(0).toUpperCase() + os.slice(1)
    : "";

  const displayBrowser = browser
    ? browser === "edg"
      ? "Edge"
      : browser.charAt(0).toUpperCase() + browser.slice(1)
    : "";

  return `${displayOS}${
    displayOS && displayBrowser ? " / " : ""
  }${displayBrowser}`;
}
