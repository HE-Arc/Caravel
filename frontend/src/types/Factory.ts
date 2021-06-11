import moment from "moment";
import { Subject } from "./subject";
import { TaskForm } from "./taskForm";

export default class Factory {
  static getSubject(): Subject {
    return {
      id: "",
      name: "",
      ects: 1,
      color: "#1976D2",
    };
  }

  static getTaskForm(): TaskForm {
    return {
      id: -1,
      title: "",
      description: "",
      start_at: moment().toDate(),
      due_at: moment().add(1, "days").toDate(),
      isPrivate: false,
      subject_id: -1,
      tasktype_id: -1,
    };
  }
}
