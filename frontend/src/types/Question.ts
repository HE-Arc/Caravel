import Comment from "./Comment";
import Timestamps from "./Timestamps";

export default interface Question extends Timestamps {
  id: string;
  title: string;
  user_id: string;
  description: string;
  solved_by: Comment | undefined;
  task_id: string;
  comments: Comment[];
  question_task_id: string;
  count_comments: string;
}
