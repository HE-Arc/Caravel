<template>
  <div>
    <div class="comment mb-3" :id="'comment-' + comment.id">
      <v-avatar color="primary" class="avatar-comment mt-2 mr-2" size="32">
        <v-img v-if="author.picture" :src="author.picture"></v-img>
        <span v-else class="white--text text-h6">
          {{ author.name | initials }}
        </span>
      </v-avatar>
      <v-card outlined :class="isSolver ? 'green lighten-4' : ''">
        <v-toolbar flat dense color="blue-grey lighten-5">
          <v-toolbar-title class="text-subtitle-2 font-weight-light">
            <strong class="mr-1">{{ author.name }}</strong>
            <timeago :datetime="comment.created_at" :auto-update="60"></timeago>
            <router-link :to="{ hash: '#comment-' + comment.id }" class="ml-1">
              <span>{{ "#" + comment.comment_question_id }}</span>
            </router-link>
            <v-chip
              class="ml-2"
              small
              color="primary"
              v-if="isTeacher"
              outlined
              label
            >
              {{ $t("global.roles.teacher") }}
            </v-chip>
            <v-chip
              class="ml-2"
              color="orange"
              small
              v-if="isAuthor"
              outlined
              label
            >
              {{ $t("global.author") }}
            </v-chip>
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-tooltip bottom v-if="isQuestionAuthor && !comment.removed">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                small
                color="success"
                :outlined="isSolver"
                @click="markSolved"
                v-bind="attrs"
                v-on="on"
              >
                <v-icon small>mdi-check-decagram-outline</v-icon>
              </v-btn>
            </template>
            {{
              isSolver
                ? $t("questions.mark_unsolved")
                : $t("questions.mark_solved")
            }}
          </v-tooltip>
          <v-btn
            icon
            small
            :outlined="showFormReply"
            @click="replyTo"
            v-if="!selected && !comment.removed"
          >
            <v-icon small>mdi-reply</v-icon>
          </v-btn>
          <v-btn
            icon
            small
            v-if="isLoggedAuthor && !comment.removed"
            :outlined="showFormEdit"
            @click="edit"
          >
            <v-icon small>mdi-pencil</v-icon>
          </v-btn>
          <v-btn
            icon
            small
            color="error"
            v-if="isLoggedAuthor && !comment.removed"
            @click="remove"
          >
            <v-icon small>mdi-delete</v-icon>
          </v-btn>
        </v-toolbar>
        <v-card-text>
          <markdown-it-vue
            class="md-comment md-body"
            :content="comment.description"
            v-if="!showFormEdit"
          />
          <comment-form
            :commentId="comment.id"
            :questionId="question.id"
            :description="comment.description"
            @save="edit"
            v-else
          />
        </v-card-text>
        <v-card-actions v-if="showFormReply">
          <comment-form
            :questionId="question.id"
            :replyTo="comment.id"
            @save="replyTo"
          />
        </v-card-actions>
      </v-card>
    </div>
    <div v-if="!selected && comment.reply_to.length > 0">
      <comment-item
        v-for="reply in comment.reply_to"
        :key="reply.id"
        :comment="reply"
        :question="question"
        class="ml-5"
      ></comment-item>
    </div>
  </div>
</template>

<script lang="ts">
import Comment from "@/types/Comment";
import { Component, Vue, Prop } from "vue-property-decorator";
import memberModule from "@/store/modules/members";
import userModule from "@/store/modules/user";
import { Member } from "@/types/member";
import MarkdownItVue from "markdown-it-vue";
import Question from "@/types/Question";
import CommentForm from "@/components/task/Comment/CommentDetails.vue";
import questionModule from "@/store/modules/questions";
import QuestionForm from "@/types/QuestionForm";

@Component({
  name: "CommentItem",
  components: {
    MarkdownItVue,
    CommentForm,
  },
})
export default class CommentItem extends Vue {
  @Prop() comment!: Comment;
  @Prop() question!: Question;
  @Prop({ default: false }) selected!: boolean;
  showFormReply = false;
  showFormEdit = false;

  get author(): Member | undefined {
    if (!this.comment) return undefined;
    return memberModule.getMember(this.comment.user_id);
  }

  get isAuthor(): boolean {
    return this.comment.user_id == this.question.user_id;
  }

  get isQuestionAuthor(): boolean {
    if (!this.author) return false;
    return userModule.user?.id == this.question.user_id;
  }

  get isLoggedAuthor(): boolean {
    if (!this.author) return false;
    return userModule.user?.id == this.comment.user_id;
  }

  get isTeacher(): boolean {
    return this.author ? this.author.isTeacher : false;
  }

  get isSolver(): boolean {
    return this.question.solved_by?.id == this.comment.id;
  }

  replyTo(): void {
    this.showFormReply = !this.showFormReply;
  }

  edit(): void {
    this.showFormEdit = !this.showFormEdit;
  }

  async remove(): Promise<void> {
    try {
      await questionModule.deleteComment(this.comment);
    } catch (err) {
      console.log(err);
    }
  }

  async markSolved(): Promise<void> {
    try {
      let questionForm = this.question as unknown as QuestionForm;
      questionForm.solved = this.isSolver ? "" : this.comment.id;
      await questionModule.upsertQuestion(questionForm);
      this.$toast.success(this.$t("questions.solved").toString());
    } catch (err) {
      console.log(err);
    }
  }
}
</script>

<style lang="scss" scoped>
.comment {
  display: flex;
  .v-avatar {
    float: left;
  }
  .v-card {
    width: 90%;
  }
}
</style>
