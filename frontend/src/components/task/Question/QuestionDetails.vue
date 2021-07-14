<template>
  <div class="question-form mb-3">
    <v-card>
      <v-card-text>
        <v-text-field
          class="mb-5"
          :label="$t('questions.form.title')"
          v-model="questionForm.title"
          :error-messages="errors.title"
          @input="errors.title = []"
        ></v-text-field>
        <v-markdown-editor
          v-model="questionForm.description"
          :errors="errors.description"
          @input="errors.description = []"
        ></v-markdown-editor>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="success" @click="save" :loading="isLoading">
          {{ questionId ? $t("global.save") : $t("global.add") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Emit } from "vue-property-decorator";
import userModule from "@/store/modules/user";
import { User } from "@/types/user";
import Factory from "@/types/Factory";
import VMarkdownEditor from "@/components/utility/markdown.vue";
import QuestionForm from "@/types/QuestionForm";
import questionModule from "@/store/modules/questions";

@Component({
  components: {
    VMarkdownEditor,
  },
})
export default class QuestionDetails extends Vue {
  @Prop() questionId?: string;
  @Prop() title?: string;
  @Prop() description?: string;
  @Prop() task_id!: string;
  isLoading = false;

  questionForm: QuestionForm = Factory.getQuestionForm();
  errors = {};

  get author(): User | undefined {
    return userModule.user;
  }

  mounted(): void {
    if (this.title) this.questionForm.title = this.title;
    if (this.description) this.questionForm.description = this.description;
  }

  clean(): void {
    this.questionForm.description = "";
    this.questionForm.title = "";
  }

  async save(): Promise<void> {
    this.questionForm.id = this.questionId;
    this.questionForm.task_id = this.task_id;
    this.isLoading = true;
    try {
      await questionModule.upsertQuestion(this.questionForm);
      this.clean();
      this.handleSave();
    } catch (err) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error_form").toString());
    } finally {
      this.isLoading = false;
    }
  }

  @Emit("save")
  handleSave(): boolean {
    return true;
  }
}
</script>

<style lang="scss" scoped>
.comment-form {
  .v-avatar {
    float: left;
  }
  .v-markdown-editor {
    width: 90%;
  }
}
</style>
