<template>
  <div>
    <h2 class="mb-2">{{ $tc("questions.label", count, [count]) }}</h2>

    <v-expansion-panels popout>
      <question-item
        v-for="question in questions"
        :key="question.id"
        :question="question"
      ></question-item>
    </v-expansion-panels>

    <div class="text-h6 font-weight-light mt-8 mb-3">
      {{ $t("questions.add") }}
    </div>
    <question-details :task_id="taskId" />
  </div>
</template>

<script lang="ts">
import Question from "@/types/Question";
import { Component, Vue } from "vue-property-decorator";
import questionModule from "@/store/modules/questions";
import QuestionItem from "@/components/task/Question/QuestionItem.vue";
import QuestionDetails from "@/components/task/Question/QuestionDetails.vue";
import taskModule from "@/store/modules/tasks";

@Component({
  components: {
    QuestionItem,
    QuestionDetails,
  },
})
export default class Questions extends Vue {
  get questions(): Question[] {
    return questionModule.questions;
  }

  get count(): number {
    return this.questions.length;
  }

  get taskId(): string {
    return taskModule.selectedId;
  }
}
</script>
