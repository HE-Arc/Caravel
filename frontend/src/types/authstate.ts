namespace Types {
  export interface AuthState {
    user: Types.User | null;
    status: string;
    token: string;
  }
}