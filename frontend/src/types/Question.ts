import Comment from "./Comment";
import Timestamps from "./Timestamps";

export default interface Question extends Timestamps {
    id: string;
    title: string;
    user_id: string;
    description: string;
    isSolved: boolean;
    task_id: string;
    comments: Comment[];
}