import moment from "moment";
import CommentForm from "./CommentForm";
import QuestionForm from "./QuestionForm";
import { Subject } from "./subject";
import { TaskForm } from "./taskForm";

export default class Factory {
  static getQuestionForm(): QuestionForm {
    return {
      id: undefined,
      title: "",
      description: "",
      task_id: "",
      solved: undefined,
    };
  }
  static getSubject(): Subject {
    return {
      id: "",
      name: "",
      ects: 4,
      color: "#1976D2",
      description: "",
      created_at: "",
    };
  }

  static getTaskForm(): TaskForm {
    return {
      id: -1,
      title: "",
      description: "",
      start_at: moment().toISOString().substr(0, 10),
      due_at: moment().add(1, "days").toISOString().substr(0, 10),
      isPrivate: false,
      subject_id: "",
      tasktype_id: "",
    };
  }

  static getCommentForm(): CommentForm {
    return {
      id: undefined,
      reply_to: undefined,
      description: "",
      question_id: "",
    };
  }
}
