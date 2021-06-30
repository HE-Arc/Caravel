export default interface QuestionForm {
    id: string | undefined;
    title: string;
    description: string;
    solved: string | undefined;
    task_id: string;
}