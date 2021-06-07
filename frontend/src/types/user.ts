import { Group } from "./group";
export interface User {
  id: Number;
  name: string;
  email: string;
  timezone: string;
  picture: string;
  picture_full: string;
  isLDAP: boolean;
  isTeacher: boolean;
  groups_available: Group[];
}
