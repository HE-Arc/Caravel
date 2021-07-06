<template>
  <div>
    <mavon-editor
      v-bind="$attrs"
      v-on="$listeners"
      language="fr"
      :toolbars="options"
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
          @click="openDialog"
        >
          <v-icon small>mdi-file</v-icon>
        </button>
      </template>
    </mavon-editor>
    <upload-modal ref="modal" />
  </div>
</template>

<script lang="ts">
import { Vue, Component, Ref } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";
import UploadModal from "@/components/utility/UploadModal.vue";

@Component({
  components: {
    UploadModal,
  },
})
export default class VMarkdownEditor extends Vue {
  @Ref() readonly modal!: UploadModal;
  imageTypes = ["image/png", "image/jpg", "image/jpeg", "image/gif"];
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
    preview: false,
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
    const max: number =
      parseInt(process.env.VUE_APP_MARKDOWN_FILE_MAX_SIZE) ?? 4194304;

    const sizeMb = max / (1024 * 1024);
    if (file.size > max) {
      this.$toast.error(
        this.$t("global.errors.toobig", [sizeMb.toString()]).toString()
      );
      return;
    }

    const $vm: unknown = this.$refs.mavon;
    const filelink = await groupModule.uploadFile(file);
    let insert = `[${file.name}](${filelink})`;
    insert = this.imageTypes.includes(file.type) ? "!" + insert : insert;
    /* eslint-disable */
    /* @ts-ignore */
    $vm.insertText($vm.getTextareaDom(), {
      prefix: insert,
      subfix: "",
      str: "",
    });
  }

  async openDialog(): Promise<void> {
    const file = await this.modal.open();

    if (file) {
      this.handleFile(file);
    }
  }
}
</script>

<style lang="scss" scoped>
.v-note-wrapper {
  z-index: auto;
}
</style>
