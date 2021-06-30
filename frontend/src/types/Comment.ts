import { Data } from "./data";
import Timestamps from "./Timestamps";

export default interface Comment extends Timestamps, Data {
    description: string;
    user_id: string;
    reply_to: Comment[];
    comment_question_id: string;
    question_id: string;
    remove: boolean;
}