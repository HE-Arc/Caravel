<template>
  <div class="comment-form mb-10">
    <v-avatar
      color="primary"
      class="avatar-comment mt-2 mr-2"
      size="32"
      v-if="!commentId"
    >
      <v-img v-if="author && author.picture" :src="author.picture"></v-img>
      <span v-else class="white--text text-h6">
        {{ author.name | initials }}
      </span>
    </v-avatar>
    <v-markdown-editor
      v-model="commentForm.description"
      :value="description"
      @input="updateDescription"
      :errors="errors.description"
    ></v-markdown-editor>
    <v-btn
      color="success"
      class="float-right mt-3"
      @click="save"
      :loading="isLoading"
    >
      {{ commentId ? $t("global.save") : $t("global.add") }}
    </v-btn>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Emit } from "vue-property-decorator";
import userModule from "@/store/modules/user";
import questionModule from "@/store/modules/questions";
import { User } from "@/types/user";
import Factory from "@/types/Factory";
import VMarkdownEditor from "@/components/utility/markdown.vue";
import CommentForm from "@/types/CommentForm";

@Component({
  components: {
    VMarkdownEditor,
  },
})
export default class CommentDetails extends Vue {
  @Prop({ default: undefined }) commentId?: string;
  @Prop({ default: "" }) description!: string;
  @Prop({ default: undefined }) replyTo?: string;
  @Prop({ required: true }) questionId!: string;
  commentForm: CommentForm = Factory.getCommentForm();
  errors = {};
  isLoading = false;

  get author(): User | undefined {
    return userModule.user;
  }

  mounted(): void {
    this.commentForm.description = this.description;
  }

  async save(): Promise<void> {
    this.commentForm.reply_to = this.replyTo;
    this.commentForm.question_id = this.questionId;
    this.commentForm.id = this.commentId;
    this.isLoading = true;

    try {
      await questionModule.upsertComment(this.commentForm);
      this.commentForm.description = "";
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

  updateDescription(value: string): void {
    this.errors["description"] = [];
    this.commentForm.description = value;
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
