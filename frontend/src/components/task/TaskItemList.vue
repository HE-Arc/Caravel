<template>
  <v-list-item
    v-if="task"
    class="task-item-list mb-2"
    :class="{ finished: task.has_finished }"
    :to="{
      name: 'taskDisplay',
      params: {
        task_id: task.id,
      },
    }"
  >
    <v-list-item-icon>
      <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-icon v-bind="attrs" v-on="on">
            {{ $t(`task.types.${task.tasktype_id}.icon`) }}
          </v-icon>
        </template>
        <span>{{ $tc(`task.types.${task.tasktype_id}.label`, 1) }}</span>
      </v-tooltip>
    </v-list-item-icon>
    <v-list-item-content>
      <v-list-item-title>
        {{ task.title }}
      </v-list-item-title>
      <v-list-item-subtitle v-if="hasDueDate">
        <timeago :datetime="task.due_at"></timeago>
      </v-list-item-subtitle>
      <v-list-item-subtitle v-else>
        {{ $t("task.createBy") }}
        <v-avatar color="primary" class="profile" size="16">
          <v-img v-if="author && author.picture" :src="author.picture"></v-img>
        </v-avatar>
        {{ author.firstname }}
        <timeago :datetime="task.created_at"></timeago>
        <span class="ml-2" v-if="hasQuestions"
          ><v-icon small>mdi-message-question-outline</v-icon>
          {{ task.questions.length }}</span
        >
        <span class="ml-2" v-if="hasReactions"
          ><v-icon small>mdi-drama-masks</v-icon> {{ countReactions }}</span
        >
      </v-list-item-subtitle>
    </v-list-item-content>
    <v-list-item-action-text>
      <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-icon
            @click.prevent="finished"
            class="mr-2"
            :color="task.has_finished ? 'success' : 'default'"
            v-bind="attrs"
            v-on="on"
          >
            {{
              task.has_finished
                ? "mdi-checkbox-marked"
                : "mdi-checkbox-blank-outline"
            }}
          </v-icon>
        </template>
        <span>{{ $t("task.finish.tooltip") }}</span>
      </v-tooltip>
      <v-icon v-if="task.isPrivate" class="mr-1">mdi-lock</v-icon>
      <v-chip small :color="subject.color" :dark="isTextDark">
        {{ subject.name }}
      </v-chip>
    </v-list-item-action-text>
  </v-list-item>
</template>

<script lang="ts">
import { Task } from "@/types/task";
import { Component, Prop, Vue } from "vue-property-decorator";
import memberModule from "@/store/modules/members";
import subjectModule from "@/store/modules/subjects";
import taskModule from "@/store/modules/tasks";
import { Member } from "@/types/member";
import { Subject } from "@/types/subject";
import TinyColor from "tinycolor2";
import { TaskType } from "@/types/helpers";

@Component
export default class TaskItemList extends Vue {
  @Prop() task!: Task;
  @Prop({ default: false }) hasDueDate!: boolean;

  get author(): Member | undefined {
    return memberModule.getMember(this.task.user_id);
  }

  get subject(): Subject | undefined {
    return subjectModule.getSubject(this.task.subject_id);
  }

  get isTextDark(): boolean {
    if (!this.subject) return false;
    const color = new TinyColor(this.subject.color);
    return color.getLuminance() < 0.228;
  }

  get isProject(): boolean {
    return this.task.tasktype_id == TaskType.PROJECT.toString();
  }

  get countReactions(): number {
    return this.task.reactions_list.reduce((a, b) => a + b.count, 0);
  }

  get hasQuestions(): boolean {
    return this.task.questions && this.task.questions.length > 0;
  }

  get hasReactions(): boolean {
    return this.task.reactions_list && this.task.reactions_list.length > 0;
  }

  async finished(): Promise<void> {
    this.task.has_finished = !this.task.has_finished;

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
.task-item-list {
  border: 1px solid #e8e8e8;
  border-radius: 10px;
  &.finished {
    opacity: 0.5;
  }
}
</style>
