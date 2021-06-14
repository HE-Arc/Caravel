export interface TaskForm {
  id: number;
  title: string;
  description: string;
  start_at: string;
  due_at: string;
  isPrivate: boolean;
  subject_id: number;
  tasktype_id: number;
}
