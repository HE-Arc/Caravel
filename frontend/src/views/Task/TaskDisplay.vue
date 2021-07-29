<template>
  <div v-if="task">
    <v-row dense>
      <v-col cols="12">
        <v-card flat>
          <v-toolbar flat class="header-task">
            <v-toolbar-title class="text-subtitle-1 font-weight-light">
              <v-chip
                :color="subject.color"
                v-text="subject.name"
                label
                small
                class="mr-1"
                :dark="isTextDark"
              />
              <v-chip
                :color="isDue ? 'success' : 'error'"
                v-text="
                  isDue ? $t('task.states.open') : $t('task.states.close')
                "
                outlined
                label
                small
              />
              <span class="pl-1">
                {{ $t("task.createBy") }}
              </span>
              <v-avatar color="primary" class="profile" size="16">
                <v-img v-if="author.picture" :src="author.picture"></v-img>
              </v-avatar>
              {{ author.firstname }}
              <timeago :datetime="task.created_at" :auto-update="60"></timeago>
              <v-icon v-if="task.histories_list.length > 0" dense
                >mdi-circle-small</v-icon
              >
              <history-list
                class="text-caption"
                :histories="task.histories_list"
              />
            </v-toolbar-title>

            <v-spacer></v-spacer>
            <v-checkbox
              small
              v-model="task.has_finished"
              color="primary"
              @click="finished"
              dense
              hide-details=""
              :label="$t('task.finish.label')"
            />
          </v-toolbar>
          <v-card-title>
            <div class="text-h5">
              {{ task.title }} <v-icon v-if="task.isPrivate">mdi-lock</v-icon>
            </div>
            <v-spacer></v-spacer>
            <v-btn small class="mr-1" :to="{ name: 'taskEdit' }">
              <v-icon small> mdi-pencil </v-icon>
            </v-btn>
            <v-btn small @click="delTask" color="error" v-if="isAuthor">
              <v-icon small> mdi-delete </v-icon>
            </v-btn>
          </v-card-title>
          <v-card-subtitle>
            <v-icon>mdi-calendar</v-icon>
            {{ dueDate.format("dddd, LL") | capitalize }}
          </v-card-subtitle>
          <v-card-text>
            <markdown-it-vue class="md-body" :content="task.description" />
          </v-card-text>
          <v-card-actions v-if="!task.isPrivate">
            <reactions :task="task"></reactions>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" v-if="!task.isPrivate">
        <questions></questions>
      </v-col>
      <confirm-modal ref="confirm" />
    </v-row>
  </div>
</template>

<script lang="ts">
import { Task } from "@/types/task";
import { Component, Vue, Watch, Ref } from "vue-property-decorator";
import taskModule from "@/store/modules/tasks";
import subjectModule from "@/store/modules/subjects";
import memberModule from "@/store/modules/members";
import userModule from "@/store/modules/user";
import moment, { Moment } from "moment";
import { Member } from "@/types/member";
import { Subject } from "@/types/subject";
import MarkdownItVue from "markdown-it-vue";
import "markdown-it-vue/dist/markdown-it-vue.css";
import TinyColor from "tinycolor2";
import Reactions from "@/components/task/Reactions.vue";
import Questions from "@/components/task/Question/Questions.vue";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";
import HistoryList from "@/components/task/HistoryList.vue";

@Component({
  components: {
    MarkdownItVue,
    Reactions,
    Questions,
    ConfirmModal,
    HistoryList,
  },
})
export default class TaskDisplay extends Vue {
  @Ref() readonly confirm!: ConfirmModal;

  get task(): Task | undefined {
    return taskModule.getCurrentTask;
  }

  get dueDate(): Moment {
    return moment(this.task?.due_at);
  }

  get createdDate(): Moment {
    return moment(this.task?.created_at);
  }

  get isDue(): boolean {
    if (!this.task) return false;
    return moment().endOf("day").isBefore(this.dueDate.endOf("day"));
  }

  get author(): Member | undefined {
    if (!this.task) return undefined;
    return memberModule.getMember(this.task.user_id);
  }

  get isAuthor(): boolean {
    return userModule.user?.id == this.author?.id;
  }

  get subject(): Subject | undefined {
    if (!this.task) return undefined;
    return subjectModule.getSubject(this.task.subject_id);
  }

  get isTextDark(): boolean {
    const color = new TinyColor(this.subject?.color);
    return color.getLuminance() < 0.228;
  }

  async delTask(): Promise<void> {
    const reply = await this.confirm.open();
    if (reply) {
      if (!this.task) return;
      try {
        await taskModule.delete(this.task);
        this.$router.push({ name: "tasks" });
        this.$toast.success(this.$t("global.success").toString());
      } catch (err) {
        this.$toast.error(this.$t("global.errors.unknown").toString());
      }
    }
  }

  mounted(): void {
    this.loadTask();
  }

  @Watch("$route.params.task_id")
  updateTask(): void {
    this.loadTask();
  }

  async loadTask(): Promise<void> {
    try {
      const taskId = this.$route.params.task_id;
      await taskModule.selectTask(taskId);
    } catch (err) {
      if (err.response.status == 404) {
        this.$router.replace({ name: "NotFound" });
      }
    }
  }

  async finished(): Promise<void> {
    if (!this.task) return;

    const data = {
      task_id: this.task.id,
      hasFinished: this.task.has_finished,
    };
    try {
      await taskModule.setFinish(data);
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(this.$t("global.errors.unknown").toString());
    }
  }
}
</script>

<style lang="scss" scoped>
.header-task {
  border-bottom: 1px solid #efefef;
}

.text-h5 .v-icon {
  vertical-align: unset;
}
</style>
