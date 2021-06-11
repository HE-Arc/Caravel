export enum GroupStatus {
  PENDING = 0,
  REFUSED = 1,
  ACCEPTED = 2,
}

export enum TaskType {
  ASSIGNMENT = 1,
  EXAM = 2,
  PROJECT = 3,
}

export interface GroupForm {
  id: number;
  name: string;
  description: string;
  picture: File | undefined;
  isPrivate: boolean | undefined;
}

export interface GroupEditForm {
  id: number;
  name: string;
  description: string;
}

export type Dictionary<T> = { [key: string]: T };
