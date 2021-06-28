<template>
  <div>{{ comment.description }}</div>
</template>

<script lang="ts">
import Comment from "@/types/Comment";
import { Component, Vue, Prop } from "vue-property-decorator";

@Component
export default class CommentItem extends Vue {
  @Prop() comment!: Comment;
}
</script>
