<template>
  <mavon-editor
    v-bind="$attrs"
    v-on="$listeners"
    language="fr"
    :toolbars="options"
    @imgAdd="uploadFile"
    ref="mavon"
  >
    <template v-slot:left-toolbar-after>
      <button
        type="button"
        class="op-icon fa fa-mavon-paperclip"
        title="test"
        aria-hidden="true"
        @click="test"
      ></button>
    </template>
  </mavon-editor>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";

@Component
export default class VMarkdownEditor extends Vue {
  options = {
    bold: true,
    italic: true,
    header: true,
    underline: true,
    strikethrough: true,
    mark: true,
    superscript: true,
    subscript: true,
    quote: true,
    ol: true,
    ul: true,
    link: true,
    image: true,
    code: true,
    table: true,
    fullscreen: true,
    readmodel: true,
    help: true,
    undo: true,
    redo: true,
    trash: true,
    save: true,
    navigation: true,
    alignleft: true,
    aligncenter: true,
    alignright: true,
    subfield: true,
    preview: true,
  };

  mounted(): void {
    let select = document.querySelector(".v-note-wrapper.markdown-body");
    if (select)
      select.addEventListener("drop", function (e: unknown) {
        console.log("ça marche", e);
      });
  }

  uploadFile(): void {
    //TODO upload
  }

  test(): void {
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
