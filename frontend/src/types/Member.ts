import { Data } from "./data";

export interface Member extends Data {
  name: string;
  email: string;
  picture: string;
  isTeacher: boolean;
  status: number;
}
