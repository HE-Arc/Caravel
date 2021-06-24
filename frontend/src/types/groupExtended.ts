import { Group } from "./group";
import { Member } from "./member";
import { Subject } from "./subject";
import { Task } from "./task";

export interface GroupExtended extends Group {
  name: string;
  description: string;
  picture: string | undefined;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
  members: Member[];
  subjects: Subject[];
  tasks: Task[];
}
