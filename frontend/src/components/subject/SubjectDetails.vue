<template>
  <v-dialog
    :value="showDialog"
    max-width="500px"
    :persistent="isMenuOpen"
    @click:outside="close"
  >
    <v-card v-if="subject">
      <v-card-title>
        {{ subject.id == "" ? $t("subject.new") : $t("subject.edit") }}
      </v-card-title>
      <v-card-text>
        <v-text-field
          :label="$t('subject.form.name.label')"
          :placeholder="$t('subject.form.name.placeholder')"
          v-model="subject.name"
          :error-messages="errors.name"
          autocomplete="off"
          @input="errors.name = []"
        ></v-text-field>
        <v-text-field
          v-model="subject.color"
          class="ma-0 pa-0"
          solo
          :error-messages="errors.color"
          @input="errors.color = []"
        >
          <template v-slot:append>
            <v-menu
              v-model="menu"
              top
              nudge-bottom="105"
              nudge-left="16"
              :close-on-content-click="false"
            >
              <template v-slot:activator="{ on }">
                <div :style="swatchStyle" v-on="on" />
              </template>
              <v-card>
                <v-card-text class="pa-0">
                  <v-color-picker v-model="subject.color" flat />
                </v-card-text>
              </v-card>
            </v-menu>
          </template>
        </v-text-field>
        <v-text-field
          type="number"
          autocomplete="off"
          v-model.number="subject.ects"
          :label="$t('subject.form.ects.label')"
          :placeholder="$t('subject.form.ects.placeholder')"
          :messages="$t('subject.form.ects.help')"
          :error-messages="errors.ects"
          @input="errors.ects = []"
        ></v-text-field>
      </v-card-text>
      <v-card-actions>
        <v-btn color="error" text @click="close">
          {{ $t("global.close") }}
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="save">{{
          $t("global.save")
        }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Subject } from "@/types/subject";
import { Vue, Component } from "vue-property-decorator";
import subjectModule from "@/store/modules/subjects";
import Factory from "@/types/Factory";
import { Dictionary } from "@/types/helpers";

@Component
export default class SubjectDetails extends Vue {
  showDialog = false;
  subject = Factory.getSubject();
  resolve: ((value: Subject | PromiseLike<Subject>) => void) | undefined;
  reject: ((value: boolean | PromiseLike<boolean>) => void) | undefined;
  errors: Dictionary<string | string[]> = {};
  menu = false;

  get isMenuOpen(): boolean {
    return this.menu;
  }

  public open(subject: Subject = Factory.getSubject()): Promise<Subject> {
    this.subject = JSON.parse(JSON.stringify(subject));
    this.showDialog = true;

    return new Promise<Subject>((resolve, reject) => {
      this.resolve = resolve;
      this.reject = reject;
    });
  }

  async save(): Promise<void> {
    try {
      const subject = await subjectModule.save(this.subject);

      this.errors = {};
      this.showDialog = false;

      if (this.resolve) this.resolve(subject);
    } catch (err) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error-form").toString());
    }
  }

  close(): void {
    this.errors = {};
    this.showDialog = false;
    if (this.reject) this.reject(false);
  }

  get swatchStyle(): Dictionary<string> {
    const [color, menu] = [this.subject.color, this.menu];
    return {
      backgroundColor: color,
      cursor: "pointer",
      height: "30px",
      width: "30px",
      borderRadius: menu ? "50%" : "4px",
      transition: "border-radius 200ms ease-in-out",
    };
  }
}
</script>
