import Question from "./Question";
import { Task } from "./task";

export default interface TaskExtended extends Task {
  questions: Question[];
}
