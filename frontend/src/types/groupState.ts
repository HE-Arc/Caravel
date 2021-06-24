import { Group } from "./Group";
import { Task } from "./Task";

export interface GroupState {
  group: Group | undefined;
  status: string;
  tasks: Task[];
}
