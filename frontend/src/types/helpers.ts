export interface Credentials {
  mail: string;
  password: string;
}

export enum JoinStatus {
  PENDING = 0,
  REFUSED = 1,
  ACCEPTED = 2,
}

export interface GroupForm {
  id: number;
  name: string;
  description: string;
  picture: string;
  isPrivate: boolean;
}
