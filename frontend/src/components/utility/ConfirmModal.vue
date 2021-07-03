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
      <v-card-text
        v-show="!!message"
        class="pa-4"
        v-html="message"
      ></v-card-text>
      <v-card-actions class="pt-3">
        <v-spacer></v-spacer>
        <v-btn v-if="!options.noconfirm" text @click.native="cancel">{{
          $t("global.cancel")
        }}</v-btn>
        <v-btn color="primary" text @click.native="agree">{{
          $t("global.confirm")
        }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue } from "vue-property-decorator";

@Component
export default class ConfirmModal extends Vue {
  dialog = false;
  resolve: ((value: boolean | PromiseLike<boolean>) => void) | undefined;
  reject: ((value: boolean | PromiseLike<boolean>) => void) | undefined;
  message = "";
  title = "";
  options = {
    color: "",
    width: 400,
    zIndex: 200,
    noconfirm: false,
  };

  open(
    title: string = this.$t("global.dialog.confirm.title").toString(),
    message: string = this.$t("global.dialog.confirm.message").toString(),
    options: Dictionary<string | number> = {}
  ): Promise<boolean> {
    this.dialog = true;
    this.title = title;
    this.message = message;
    this.options = Object.assign(this.options, options);
    return new Promise<boolean>((resolve, reject) => {
      this.resolve = resolve;
      this.reject = reject;
    });
  }

  agree(): void {
    if (!this.resolve) return;
    this.resolve(true);
    this.dialog = false;
  }

  cancel(): void {
    if (!this.resolve) return;
    this.dialog = false;
  }
}
</script>
