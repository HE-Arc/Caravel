<template>
  <v-container>
    <v-row>
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
              <timeago :datetime="task.created_at"></timeago>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn small color="success" :to="{ name: 'newTask' }">{{
              $t("global.new")
            }}</v-btn>
          </v-toolbar>
          <v-card-title>
            <div class="text-h5">
              {{ task.title }} <v-icon v-if="task.isPrivate">mdi-lock</v-icon>
            </div>
            <v-spacer></v-spacer>
            <v-btn small class="mr-1" :to="{ name: 'taskEdit' }">{{
              $t("global.edit")
            }}</v-btn>
            <v-btn small @click="delTask" color="error">
              {{ $t("global.delete") }}
            </v-btn>
          </v-card-title>
          <v-card-subtitle>
            <v-icon>mdi-calendar</v-icon>
            {{ dueDate.format("dddd, LL") | capitalize }}
          </v-card-subtitle>
          <v-card-text>
            <markdown-it-vue class="md-body" :content="task.description" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Task } from "@/types/Task";
import { Component, Vue } from "vue-property-decorator";
import taskModule from "@/store/modules/tasks";
import subjectModule from "@/store/modules/subjects";
import memberModule from "@/store/modules/members";
import moment, { Moment } from "moment";
import { Member } from "@/types/Member";
import { Subject } from "@/types/Subject";
import MarkdownItVue from "markdown-it-vue";
import "markdown-it-vue/dist/markdown-it-vue.css";
import TinyColor from "tinycolor2";

@Component({
  components: {
    MarkdownItVue,
  },
})
export default class TaskDisplay extends Vue {
  get task(): Task | undefined {
    const id = this.$route.params.task_id;
    return taskModule.getTask(id);
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

  get subject(): Subject | undefined {
    if (!this.task) return undefined;
    return subjectModule.getSubject(this.task.subject_id);
  }

  get isTextDark(): boolean {
    const color = new TinyColor(this.subject?.color);
    return color.getLuminance() < 0.228;
  }

  async delTask(): Promise<void> {
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
</script>

<style lang="scss" scoped>
.header-task {
  border-bottom: 1px solid #efefef;
}

.text-h5 .v-icon {
  vertical-align: unset;
}
</style>
