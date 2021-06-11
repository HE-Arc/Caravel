export interface TaskForm {
    id: number;
    title: string;
    description: string;
    start_at: Date;
    due_at: Date;
    isPrivate: boolean;
    subject_id: number;
    tasktype_id: number
}