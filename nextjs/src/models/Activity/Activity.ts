export type Activity = {
  id: string;
  categoryId: string;
  name: string;
  startedAt: string;
  finishedAt: string;
};

export type CreateActivity = {
  categoryId: string;
  name: string;
  startedAt: string;
  finishedAt: string;
};
