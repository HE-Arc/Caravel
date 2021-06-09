import store from "@/store";
import { Task } from "@/types/task";
import axios, { AxiosResponse } from "axios";
import {
    VuexModule,
    Module,
    Mutation,
    Action,
    getModule,
} from "vuex-module-decorators";

@Module({
    namespaced: true,
    dynamic: true,
    store,
    name: "tasks",
    preserveState: localStorage.getItem("vuex") !== null,
})
class TasksModule extends VuexModule {
    _tasks: Task[] = []
    _status = "";

    get tasks(): Task[] {
        return this._tasks;
    }

    get status(): string {
        return this._status;
    }

    @Mutation
    private ERROR() {
        this._status = "error";
    }

    @Mutation
    private REQUEST() {
        this._status = "loading";
    }

    @Mutation
    private LOAD_TASKS(tasks: Task[]) {
        this._tasks = tasks;
        this._status = "tasks_loaded";
    }

    @Mutation
    private UPSERT_TASK(task: Task) {
        let index = this._tasks.findIndex(item => item.id == task.id);
        if (index === -1) {
            this._tasks.push(task);
            this._status = "task_added";
        } else {
            this._tasks[index] = task;
            this._status = "task_modified";
        }
    }

    @Mutation
    private REMOVE_TASK(task: Task) {
        let index = this._tasks.findIndex(item => item.id == task.id);
        if (index !== -1) {
            this._tasks.splice(index, 1);
            this._status = "task_delete";
        }
    }

    @Action
    add(groupId: string, task: Task): Promise<AxiosResponse> {
        return new Promise<AxiosResponse>((resolve, reject) => {
            axios({
                url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks`,
                method: 'POST',
                data: task,
            })
                .then((response) => {
                    const data: Task = response.data;
                    this.UPSERT_TASK(data);
                    resolve(response);
                })
                .catch((err) => {
                    this.ERROR();
                    reject(err);
                });
        });
    }

    @Action
    update(groupId: string, task: Task): Promise<AxiosResponse> {
        return new Promise<AxiosResponse>((resolve, reject) => {
            axios({
                url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks/${task.id}`,
                method: 'PATCH',
                data: task,
            })
                .then((response) => {
                    const data: Task = response.data;
                    this.UPSERT_TASK(data);
                    resolve(response);
                })
                .catch((err) => {
                    this.ERROR();
                    reject(err);
                });
        });
    }

    @Action
    delete(groupId: string, task: Task): Promise<AxiosResponse> {
        return new Promise<AxiosResponse>((resolve, reject) => {
            axios({
                url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks/${task.id}`,
                method: 'DELETE',
            })
                .then((response) => {
                    this.REMOVE_TASK(task);
                    resolve(response);
                })
                .catch((err) => {
                    this.ERROR();
                    reject(err);
                });
        });
    }

    @Action
    load(tasks: Task[]) {
        this.LOAD_TASKS(tasks);
    }

}

const instance = getModule(TasksModule);

export default instance;