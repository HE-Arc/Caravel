import { User } from "./User";

export interface AuthState {
  user: User | undefined;
  status: string;
  token: string;
}
