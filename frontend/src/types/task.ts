import { Data } from "./data";
import Reaction from "./Reaction";

export interface Task extends Data {
  title: string;
  description: string;
  start_at: Date | undefined;
  due_at: Date | undefined;
  isPrivate: boolean;
  subject_id: string;
  user_id: string;
  tasktype_id: string;
  task_group_id: string;
  created_at: Date | undefined;
  reactions_list: Reaction[];
}
