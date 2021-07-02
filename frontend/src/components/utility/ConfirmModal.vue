<template>
  <v-dialog
    v-model="dialog"
    :max-width="options.width"
    :style="{ zIndex: options.zIndex }"
    @keydown.esc="cancel"
  >
    <v-card>
      <v-toolbar dark :color="options.color" dense flat>
        <v-toolbar-title class="text-body-2 font-weight-bold grey--text">
          {{ title }}
        </v-toolbar-title>
      </v-toolbar>
      <v-card-text
        v-show="!!message"
        class="pa-4 black--text"
        v-html="message"
      ></v-card-text>
      <v-card-actions class="pt-3">
        <v-spacer></v-spacer>
        <v-btn
          v-if="!options.noconfirm"
          color="grey"
          text
          class="body-2 font-weight-bold"
          @click.native="cancel"
          >{{ $t("global.cancel") }}</v-btn
        >
        <v-btn
          color="primary"
          class="body-2 font-weight-bold"
          outlined
          @click.native="agree"
          >{{ $t("global.confirm") }}</v-btn
        >
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
    color: "grey lighten-3",
    width: 400,
    zIndex: 200,
    noconfirm: false,
  };

  open(
    title: string,
    message: string,
    options: Dictionary<string | number>
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
