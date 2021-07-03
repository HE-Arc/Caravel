import { Data } from "./data";
import Notification from "./notification";
export interface User extends Data {
  name: string;
  email: string;
  timezone: string;
  picture: string;
  isLDAP: boolean;
  isTeacher: boolean;
  notifications: Notification[];
}
