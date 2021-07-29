import { User } from "./user";

export interface AuthState {
  user: User | undefined;
  status: string;
  token: string;
}
