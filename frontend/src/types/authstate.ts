import { User } from "./user";

export interface AuthState {
  user?: User;
  status: string;
  token: string;
}
