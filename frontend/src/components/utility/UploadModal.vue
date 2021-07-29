<template>
  <v-dialog
    v-model="dialog"
    :max-width="options.width"
    :style="{ zIndex: options.zIndex }"
    @keydown.esc="cancel"
  >
    <v-card>
      <v-toolbar :color="options.color" flat>
        <v-toolbar-title>
          {{ title }}
        </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-file-input
          v-model="file"
          :label="$t('global.dialog.upload.label')"
        ></v-file-input>
      </v-card-text>
      <v-card-actions class="pt-3">
        <v-spacer></v-spacer>
        <v-btn v-if="!options.noconfirm" text @click.native="cancel">{{
          $t("global.cancel")
        }}</v-btn>
        <v-btn color="primary" text @click.native="agree">{{
          $t("global.insert")
        }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue } from "vue-property-decorator";

@Component
export default class UploadModal extends Vue {
  dialog = false;
  resolve: ((value: File | PromiseLike<File>) => void) | undefined;
  reject: ((value: File | PromiseLike<File>) => void) | undefined;
  title = "";
  file: File | null = null;
  options = {
    color: "",
    width: 400,
    zIndex: 200,
    noconfirm: false,
  };

  open(
    title: string = this.$t("global.dialog.upload.title").toString(),
    options: Dictionary<string | number> = {}
  ): Promise<File> {
    this.dialog = true;
    this.title = title;
    this.options = Object.assign(this.options, options);
    return new Promise<File>((resolve, reject) => {
      this.resolve = resolve;
      this.reject = reject;
    });
  }

  agree(): void {
    if (!this.resolve || !this.file) return;
    this.resolve(this.file);
    this.clean();
  }

  cancel(): void {
    if (!this.resolve) return;
    this.clean();
  }

  clean(): void {
    this.file = null;
    this.dialog = false;
  }
}
</script>
