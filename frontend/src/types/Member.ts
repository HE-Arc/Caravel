import { Data } from "./Data";

export interface Member extends Data {
  name: string;
  firstname: string;
  lastname: string;
  email: string;
  picture: string;
  isTeacher: boolean;
  status: number;
}
