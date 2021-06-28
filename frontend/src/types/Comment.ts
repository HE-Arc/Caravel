import Timestamps from "./Timestamps";

export default interface Comment extends Timestamps {
    description: string;
    user_id: string;
    reply_to: Comment;
}