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
      start_at: moment().toISOString().substr(0, 10),
      due_at: moment().add(1, "days").toISOString().substr(0, 10),
      isPrivate: false,
      subject_id: "",
      tasktype_id: "",
    };
  }
}
