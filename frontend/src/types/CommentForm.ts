export default interface CommentForm {
  id: string | undefined;
  description: string;
  reply_to: string | undefined;
  question_id: string;
}
