import { Data } from "./data";

export interface Group extends Data {
  name: string;
  description: string;
  picture: string | undefined;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
}
