import { Group } from "./Group";
import { Member } from "./Member";
import { Subject } from "./Subject";
import { Task } from "./Task";

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
