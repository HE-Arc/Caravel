export interface Task {
  id: string;
  title: string;
  description: string;
  start_at: Date | undefined;
  due_at: Date | undefined;
  created_at: Date | undefined;
  updated_at: Date | undefined;
  isPrivate: boolean;
  subject_id: string;
  user_id: string;
  type: string;
}
