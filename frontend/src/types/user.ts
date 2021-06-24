import { Data } from "./data";
import { Group } from "./group";
import Notification from "./notification";
export interface User extends Data {
  name: string;
  email: string;
  timezone: string;
  picture: string;
  isLDAP: boolean;
  isTeacher: boolean;
  groups_available: Group[];
  notifications: Notification[];
}
