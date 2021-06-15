<template>
  <v-container>
    <v-row>
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
                  <v-autocomplete-filter
                    :label="$t('task.form.subject.label')"
                    :placeholder="$t('task.form.subject.placeholder')"
                    :manager="$router.resolve({ name: 'Home' }).href"
                    filled
                    :items="items"
                    menu-props="closeOnContentClick"
                    @create="addLabel"
                    v-model="task.subject_id"
                    dense
                    :error-messages="errors.subject_id"
                  >
                    <template v-slot:item="data">
                      <v-list-item-icon>
                        <v-icon :color="data.item.color">mdi-square</v-icon>
                      </v-list-item-icon>
                      <v-list-item-content>
                        <v-list-item-title>
                          {{ data.item.text }}
                        </v-list-item-title>
                      </v-list-item-content>
                    </template>
                    <template v-slot:selection="data">
                      <v-icon :color="data.item.color">mdi-square</v-icon>
                      {{ data.item.text }}
                    </template>
                  </v-autocomplete-filter>
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
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-select
                    :items="types"
                    :label="$t('task.form.type.label')"
                    :placeholder="$t('task.form.type.placeholder')"
                    filled
                    v-model.number="task.tasktype_id"
                    dense
                    :error-messages="errors.tasktype_id"
                  >
                    <template v-slot:item="{ item }">
                      <v-icon v-text="item.icon" class="mr-3" />
                      {{ item.text }}
                    </template>
                    <template v-slot:selection="{ item }">
                      <v-icon v-text="item.icon" class="mr-3" />
                      {{ item.text }}
                    </template>
                  </v-select>
                </v-col>
                <v-col cols="6">
                  <simple-date-picker
                    v-model="task.due_at"
                    filled
                    :label="$t('task.form.due.label')"
                    :placeholder="$t('task.form.due.placeholder')"
                    dense
                    :error-messages="errors.due_at"
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
                  ></v-switch>
                </v-col>
              </v-row>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" @click="save">{{
                $t("global.save")
              }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-form>
        <subject-details
          :subjectData="subject"
          :isActive="openSubjectForm"
          @close="openSubjectForm = false"
          @handle-subject="handleNewSubject"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { TaskForm } from "@/types/taskForm";
import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import VAutocompleteFilter from "@/components/utility/VAutocompleteFilter.vue";
import { Dictionary, TaskType } from "@/types/helpers";
import subjectModule from "@/store/modules/subjects";
import taskModule from "@/store/modules/tasks";
import { Subject } from "@/types/subject";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import SimpleDatePicker from "@/components/utility/simpledatepicker.vue";
import VMarkdownEditor from "@/components/utility/markdown.vue";
import moment from "moment";
import { Task } from "@/types/task";

@Component({
  components: {
    VAutocompleteFilter,
    SubjectDetails,
    SimpleDatePicker,
    VMarkdownEditor,
  },
})
export default class TaskDetails extends Vue {
  @Prop({
    default: () => Factory.getTaskForm(),
  })
  data!: TaskForm;

  errors = {};
  task = this.data;
  subjectModal: Subject = Factory.getSubject();
  openSubjectForm = false;
  typesEnum = TaskType;

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
    return this.task.tasktype_id == TaskType.PROJECT;
  }

  get items(): Dictionary<string | number>[] {
    const subjects: Subject[] = subjectModule.subjects;
    if (!subjects) return [];
    return subjects.map((item: Subject) => ({
      value: item.id,
      text: item.name,
      color: item.color,
    }));
  }

  get types(): Dictionary<string | number>[] {
    return Object.values(TaskType)
      .filter((key) => Number.isInteger(key))
      .map((key) => ({
        value: key,
        text: this.$tc(`task.types.${key}.label`, 0).toString(),
        icon: this.$t(`task.types.${key}.icon`).toString(),
      }));
  }

  get subject(): Subject {
    return this.subjectModal;
  }

  addLabel(text: string): void {
    this.subjectModal.name = text;
    this.openSubjectForm = true;
  }

  handleNewSubject(subject: Subject): void {
    this.task.subject_id = parseInt(subject.id);
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
