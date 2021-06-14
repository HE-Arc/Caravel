<template>
  <v-list-item
    class="task-item-list mb-2"
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
          <v-img v-if="author.picture" :src="author.picture"></v-img>
        </v-avatar>
        {{ author.firstname }}
        <timeago :datetime="task.created_at"></timeago>
      </v-list-item-subtitle>
    </v-list-item-content>
    <v-list-item-action>
      <v-chip small :color="subject.color" :dark="isTextDark">
        {{ subject.name }}
      </v-chip>
    </v-list-item-action>
  </v-list-item>
</template>

<script lang="ts">
import { Task } from "@/types/task";
import { Component, Prop, Vue } from "vue-property-decorator";
import memberModule from "@/store/modules/members";
import subjectModule from "@/store/modules/subjects";
import { Member } from "@/types/Member";
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
    const color = new TinyColor(this.subject?.color);
    return color.getLuminance() < 0.228;
  }

  get isProject(): boolean {
    return this.task.tasktype_id == TaskType.PROJECT.toString();
  }
}
</script>

<style lang="scss" scoped>
.task-item-list {
  border: 1px solid #e8e8e8;
  border-radius: 10px;
}
</style>
