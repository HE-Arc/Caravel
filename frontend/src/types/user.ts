import { Data } from "./Data";
import { Group } from "./Group";
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
