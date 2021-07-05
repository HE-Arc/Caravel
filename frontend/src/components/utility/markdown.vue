<template>
  <mavon-editor
    v-bind="$attrs"
    v-on="$listeners"
    language="fr"
    :toolbars="options"
    @imgAdd="uploadFile"
    ref="mavon"
    :shortCut="false"
    :subfield="false"
    :scrollStyle="true"
    :imageFilter="() => false"
  >
    <template v-slot:left-toolbar-after>
      <button
        type="button"
        class="op-icon text-center"
        title="test"
        aria-hidden="true"
        @click="uploadFile"
      >
        <v-icon small>mdi-file</v-icon>
      </button>
    </template>
  </mavon-editor>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";

@Component
export default class VMarkdownEditor extends Vue {
  options = {
    bold: true,
    italic: true,
    header: true,
    underline: true,
    strikethrough: true,
    mark: false,
    superscript: false,
    subscript: true,
    quote: true,
    ol: true,
    ul: true,
    link: true,
    image: true,
    code: true,
    table: true,
    fullscreen: true,
    readmodel: false,
    help: true,
    undo: true,
    redo: true,
    trash: false,
    save: false,
    navigation: false,
    alignleft: true,
    aligncenter: true,
    alignright: true,
    subfield: true,
    preview: true,
  };

  mounted(): void {
    let select = document.querySelector(".v-note-wrapper.markdown-body");
    if (select)
      select.addEventListener("drop", (ev: Event) => {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        const e = ev as DragEvent;

        const file = e.dataTransfer?.items[0].getAsFile();

        if (file) {
          this.handleFile(file);
        }
      });
  }

  async handleFile(file: File): Promise<void> {
    console.log(file);
    const $vm: unknown = this.$refs.mavon;
    const filelink = await groupModule.uploadFile(file);
    console.log(filelink);
    /* eslint-disable */
    /* @ts-ignore */
    $vm.insertText($vm.getTextareaDom(), {
      prefix: `${filelink}`,
      subfix: "",
      str: "",
    });
  }

  uploadFile(): void {
    const $vm: unknown = this.$refs.mavon;
    console.log($vm);
    /*$vm.insertText($vm.getTextareaDom(), {
      prefix: "![mon test])",
      subfix: "",
      str: "",
    });
    */
  }
}
</script>

<style lang="scss" scoped>
.v-note-wrapper {
  z-index: auto;
}
</style>
