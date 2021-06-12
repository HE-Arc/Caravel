<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-form>
          <v-card outlined>
            <v-toolbar flat color="primary" dark>
              <v-icon class="mr-2">mdi-pencil</v-icon>
              <v-toolbar-title class="font-weight-light">
                {{ $t("task.create") }}
              </v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-row dense>
                <v-col cols="12" sm="6">
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
                      <v-icon :color="data.item.color">mdi-square </v-icon>
                      {{ data.item.text }}
                    </template>
                  </v-autocomplete-filter>
                </v-col>
                <v-col cols="12" sm="6">
                  <simple-date-picker
                    v-model="task.due_at"
                    filled
                    :label="$t('task.form.due.label')"
                    :placeholder="$t('task.form.due.placeholder')"
                    dense
                  />
                </v-col>
                <v-col cols="12">
                  <v-select
                    :items="types"
                    :label="$t('task.form.type.label')"
                    :placeholder="$t('task.form.type.placeholder')"
                    filled
                    v-model.number="task.tasktype_id"
                    dense
                  >
                    <template v-slot:item="{ item }">
                      <v-icon v-text="item.icon" class="mr-3" />
                      {{ item.text }}
                    </template>
                    <template v-slot:selection="{ item }">
                      <v-icon v-text="item.icon" class="py-3 mr-3" />
                      {{ item.text }}
                    </template>
                  </v-select>
                  <v-text-field
                    :label="$t('task.form.title.label')"
                    :placeholder="$t('task.form.title.placeholder')"
                    v-model="task.title"
                    :error-messages="errors.title"
                    filled
                    dense
                    autocomplete="off"
                  />
                  <v-markdown-editor v-model="task.description" />
                  <v-switch
                    v-model="task.isPrivate"
                    label="Is Private Task"
                  ></v-switch>
                </v-col>
              </v-row>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" text>{{ $t("global.save") }}</v-btn>
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
import { Subject } from "@/types/subject";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import SimpleDatePicker from "@/components/utility/simpledatepicker.vue";
import VMarkdownEditor from "@/components/utility/markdown.vue";

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

  task = Factory.getTaskForm();
  subjectModal: Subject = Factory.getSubject();
  openSubjectForm = false;
  errors = [];

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
}
</script>
