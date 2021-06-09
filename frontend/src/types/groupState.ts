import { Group } from "./group";
import { Task } from "./task"

export interface GroupState {
  group: Group | undefined;
  status: string;
  tasks: Task[];
}
