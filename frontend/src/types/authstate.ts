namespace Types {
  export interface AuthState {
    user?: Types.User;
    status: string;
    token: string;
  }
}