export enum JoinStatus {
  PENDING = 0,
  REFUSED = 1,
  ACCEPTED = 2,
}

export interface GroupForm {
  id: number;
  name: string;
  description: string;
  picture: File | undefined;
  isPrivate: boolean | undefined;
}
