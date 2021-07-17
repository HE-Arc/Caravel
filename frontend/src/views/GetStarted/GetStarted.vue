<template>
  <v-container class="mt-4">
    <v-row>
      <v-col cols="12">
        <markdown-it-vue class="md-body" :content="content" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import MarkdownItVue from "markdown-it-vue";
import "markdown-it-vue/dist/markdown-it-vue.css";
import aboutmd from "@/views/GetStarted/GetStarted.md";

@Component({
  components: {
    MarkdownItVue,
  },
})
export default class GetStarted extends Vue {
  get content(): string {
    return aboutmd;
  }
}
</script>
