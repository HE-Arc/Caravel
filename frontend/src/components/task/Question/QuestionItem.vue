<template>
  <v-expansion-panel>
    <v-expansion-panel-header>
      <div>
        <div class="text-h6">
          {{ question.title | capitalize }}
          <v-chip
            class="ma-2"
            :color="question.isSolved ? 'success' : 'error'"
            text-color="white"
            small
          >
            <v-icon class="mr-1">{{
              question.isSolved
                ? "mdi-check-circle-outline"
                : "mdi-close-circle-outline"
            }}</v-icon>
            {{ $t(`questions.states.${question.isSolved}.label`) }}
          </v-chip>
        </div>
        <div class="text-subtitle-2 font-weight-light d-flex">
          <timeago :datetime="question.created_at" :auto-update="60"></timeago>
          <span v-if="hasComments" class="ml-1">
            · <v-icon small>mdi-message</v-icon> {{ commsCount }} ·
          </span>
          <v-avatar color="primary" class="profile ml-2 mr-1" size="24">
            <v-img v-if="author.picture" :src="author.picture"></v-img>
            <span v-else class="white--text text-h6">
              {{ author.name | initials }}
            </span>
          </v-avatar>
          {{ author.name }}
        </div>
      </div>
    </v-expansion-panel-header>

    <v-expansion-panel-content>
      <markdown-it-vue class="md-body" :content="question.description" />
      <v-divider class="my-4" v-if="hasComments"></v-divider>
      <commment-item
        v-for="comment in comments"
        :key="comment.id"
        :comment="comment"
      ></commment-item>
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script lang="ts">
import Question from "@/types/Question";
import { Component, Prop, Vue } from "vue-property-decorator";
import MarkdownItVue from "markdown-it-vue";
import Comment from "@/types/Comment";
import memberModule from "@/store/modules/members";
import { Member } from "@/types/member";
import CommmentItem from "@/components/task/Comment/ReactionItem.vue";

@Component({
  components: {
    MarkdownItVue,
    CommmentItem,
  },
})
export default class QuestionItem extends Vue {
  @Prop() question!: Question;

  get comments(): Comment[] {
    return this.question.comments;
  }

  get commsCount(): number {
    return this.comments.length;
  }

  get hasComments(): boolean {
    return this.commsCount > 0;
  }

  get author(): Member | undefined {
    if (!this.question) return undefined;
    return memberModule.getMember(this.question.user_id);
  }
}
</script>
