<template>
  <v-row dense v-if="task">
    <v-col cols="12">
      <v-form>
        <v-card outlined>
          <v-toolbar flat>
            <v-icon class="mr-2">mdi-pencil</v-icon>
            <v-toolbar-title class="font-weight-light">
              {{ isNewTask ? $t("task.create") : $t("task.edit") }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-row dense>
              <v-col cols="12" sm="4" md="3">
                <select-subject
                  v-model="task.subject_id"
                  :error-messages="errors.subject_id"
                  @input="errors.subject_id = []"
                ></select-subject>
              </v-col>
              <v-col cols="12" sm="8" md="9">
                <v-text-field
                  :label="$t('task.form.title.label')"
                  :placeholder="$t('task.form.title.placeholder')"
                  v-model="task.title"
                  :error-messages="errors.title"
                  filled
                  dense
                  autocomplete="off"
                  @input="errors.title = []"
                >
                </v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <select-type
                  filled
                  v-model="task.tasktype_id"
                  dense
                  :error-messages="errors.tasktype_id"
                  @input="errors.tasktype_id = []"
                ></select-type>
              </v-col>
              <v-col cols="12" md="6">
                <simple-date-picker
                  v-model="task.due_at"
                  filled
                  :label="$t('task.form.due.label')"
                  :placeholder="$t('task.form.due.placeholder')"
                  dense
                  :error-messages="errors.due_at"
                  @input="errors.due_at = []"
                />
              </v-col>
              <v-col cols="12">
                <simple-date-picker
                  v-model="task.start_at"
                  filled
                  :label="$t('task.form.startAt.label')"
                  dense
                  :max="task.due_at"
                  v-if="showStartAt"
                  :error-messages="errors.start_at"
                  @input="errors.start_at = []"
                />

                <v-markdown-editor
                  v-model="task.description"
                  :error-messages="errors.description"
                />
                <v-switch
                  v-if="isNewTask"
                  v-model="task.isPrivate"
                  :label="$t('task.form.private.label')"
                  :error-messages="errors.isPrivate"
                  @input="errors.isPrivate = []"
                ></v-switch>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="success" @click="save">{{ $t("global.save") }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { TaskForm } from "@/types/taskForm";
import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import VAutocompleteFilter from "@/components/utility/VAutocompleteFilter.vue";
import { TaskType } from "@/types/helpers";
import taskModule from "@/store/modules/tasks";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import SimpleDatePicker from "@/components/utility/simpledatepicker.vue";
import VMarkdownEditor from "@/components/utility/markdown.vue";
import moment from "moment";
import { Task } from "@/types/task";
import SelectType from "@/components/inputs/SelectType.vue";
import SelectSubject from "@/components/inputs/SelectSubject.vue";

@Component({
  components: {
    VAutocompleteFilter,
    SubjectDetails,
    SimpleDatePicker,
    VMarkdownEditor,
    SelectType,
    SelectSubject,
  },
})
export default class TaskDetails extends Vue {
  @Prop({
    default: () => Factory.getTaskForm(),
  })
  data!: TaskForm;

  errors = {};
  task = this.data;

  get isNewTask(): boolean {
    return this.task.id == -1;
  }

  get dueAt(): string {
    return this.task.due_at;
  }

  set dueAt(value: string) {
    const due = moment(value, "YYYY-MM-DD");
    const start = moment(this.task.start_at, "YYYY-MM-DD");
    this.task.due_at = value;
    if (start.isAfter(due)) this.task.start_at = value;
  }

  get showStartAt(): boolean {
    return this.task.tasktype_id == TaskType.PROJECT.toString();
  }

  @Watch("data")
  onDataChange(val: TaskForm): void {
    this.task = val;
  }

  async save(): Promise<void> {
    try {
      const task: Task = JSON.parse(JSON.stringify(this.task));
      await taskModule.save(task);
      this.$toast.success(this.$t("global.success").toString());
      this.errors = {};
      this.$router.push({ name: "tasks" });
    } catch (err) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error_form").toString());
    }
  }
}
</script>
