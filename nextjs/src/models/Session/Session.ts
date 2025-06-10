export interface Session {
  sessionId: string;
  createdAt: string;
  userAgent: string;
  lastActivity: string;
  isThisSession: boolean;
}
