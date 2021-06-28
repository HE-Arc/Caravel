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
  </div>
</template>

<script lang="ts">
import Question from "@/types/Question";
import { Component, Vue } from "vue-property-decorator";
import questionModule from "@/store/modules/questions";
import QuestionItem from "@/components/task/Question/QuestionItem.vue";

@Component({
  components: {
    QuestionItem,
  },
})
export default class Questions extends Vue {
  get questions(): Question[] {
    return questionModule.questions;
  }

  get count(): number {
    return this.questions.length;
  }
}
</script>
