<template>
  <v-expansion-panel :id="'question-' + question.question_task_id">
    <v-expansion-panel-header>
      <div>
        <div class="text-h6">
          {{ question.title | capitalize }}

          <v-chip
            class="ma-2"
            :color="isSolved ? 'success' : 'error'"
            text-color="white"
            small
          >
            <v-icon class="mr-1">{{
              isSolved ? "mdi-check-circle-outline" : "mdi-close-circle-outline"
            }}</v-icon>
            {{ $t(`questions.states.${isSolved}.label`) }}
          </v-chip>
          <router-link
            :to="{ hash: '#question-' + question.question_task_id }"
            class="ml-1 font-weight-light"
          >
            <span>{{ "#" + question.question_task_id }}</span>
          </router-link>
        </div>
        <div class="text-subtitle-2 font-weight-light d-flex">
          <timeago :datetime="question.created_at" :auto-update="60"></timeago>
          <span v-if="hasComments" class="ml-1">
            · <v-icon small>mdi-message</v-icon> {{ commsCount }} ·
          </span>
          <v-avatar color="primary" class="profile ml-2 mr-1" size="24">
            <v-img v-if="author.picture" :src="author.picture"></v-img>
            <span v-else class="white--text text-h7">
              {{ author.name | initials }}
            </span>
          </v-avatar>
          {{ author.name }}
        </div>
      </div>
    </v-expansion-panel-header>

    <v-expansion-panel-content>
      <div class="mb-4">
        <v-btn small icon :outlined="showFormEdit" @click="edit" class="mr-2">
          <v-icon small>mdi-pencil</v-icon>
        </v-btn>
        <v-btn small icon v-if="isAuthor" color="error" @click="remove">
          <v-icon small>mdi-delete</v-icon>
        </v-btn>
      </div>
      <markdown-it-vue
        v-if="!showFormEdit"
        class="md-body"
        :content="question.description"
      />
      <question-details
        :title="question.title"
        :questionId="question.id"
        :description="question.description"
        @save="edit"
        v-else
      />

      <div v-if="question.solved_by">
        <v-divider class="my-5"></v-divider>

        <div class="text-h6 font-weight-light mb-3">
          {{ $t("questions.answer") }}
        </div>
        <commment-item
          :key="question.solved_by.id"
          :comment="question.solved_by"
          :question="question"
          :selected="true"
        ></commment-item>
      </div>
      <v-divider class="my-5"></v-divider>

      <commment-item
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
        :question="question"
      ></commment-item>

      <div class="text-h6 font-weight-light mt-8 mb-3">
        {{ $t("questions.comments.add") }}
      </div>
      <comment-form :questionId="question.id" />
      <confirm-modal ref="confirm" />
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script lang="ts">
import Question from "@/types/Question";
import { Component, Prop, Vue, Ref } from "vue-property-decorator";
import MarkdownItVue from "markdown-it-vue";
import Comment from "@/types/Comment";
import memberModule from "@/store/modules/members";
import { Member } from "@/types/member";
import CommmentItem from "@/components/task/Comment/CommentItem.vue";
import CommentForm from "@/components/task/Comment/CommentDetails.vue";
import userModule from "@/store/modules/user";
import QuestionDetails from "@/components/task/Question/QuestionDetails.vue";
import questionModule from "@/store/modules/questions";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";

@Component({
  components: {
    MarkdownItVue,
    CommmentItem,
    CommentForm,
    QuestionDetails,
    ConfirmModal,
  },
})
export default class QuestionItem extends Vue {
  @Ref() readonly confirm!: ConfirmModal;
  @Prop() question!: Question;
  showFormEdit = false;

  get comments(): Comment[] {
    return this.question.comments;
  }

  get isSolved(): number {
    return this.question.solved_by ? 1 : 0;
  }

  get commsCount(): string {
    return this.question.count_comments;
  }

  get hasComments(): boolean {
    return this.comments.length > 0;
  }

  get author(): Member | undefined {
    if (!this.question) return undefined;
    return memberModule.getMember(this.question.user_id);
  }

  get isAuthor(): boolean {
    return userModule.user?.id == this.author?.id;
  }

  edit(): void {
    this.showFormEdit = !this.showFormEdit;
  }

  async remove(): Promise<void> {
    const reply = await this.confirm.open();
    if (reply) {
      try {
        await questionModule.deleteQuestion(this.question);
        this.$toast.success(this.$t("questions.delete").toString());
      } catch {
        this.$toast.error(this.$t("global.error_form").toString());
      }
    }
  }
}
</script>
