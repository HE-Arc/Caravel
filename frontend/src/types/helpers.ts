export interface Credentials {
  mail: string;
  password: string;
}

export enum JoinStatus {
  PENDING = 0,
  REFUSED = 1,
  ACCEPTED = 2,
}
