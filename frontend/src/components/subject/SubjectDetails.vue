<template>
  <v-form>
    <v-card v-if="subject">
      <v-card-title>
        {{ subject.id == "" ? $t("subject.new") : $t("subject.edit") }}
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="6">
            <v-text-field
              :label="$t('subject.form.name.label')"
              :placeholder="$t('subject.form.name.placeholder')"
              v-model="subject.name"
              :error-messages="errors.name"
              autocomplete="off"
              @input="errors.name = []"
            ></v-text-field>
          </v-col>
          <v-col cols="3">
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
          </v-col>
          <v-col cols="3">
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
          </v-col>
        </v-row>
        <v-markdown-editor
          v-model="subject.description"
          :errors="errors.description"
          @input="errors.description = []"
          class="mt-5"
        />
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="success" text @click="save">{{
          $t("global.save")
        }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script lang="ts">
import { Subject } from "@/types/subject";
import { Vue, Component, Watch } from "vue-property-decorator";
import subjectModule from "@/store/modules/subjects";
import Factory from "@/types/Factory";
import { Dictionary } from "@/types/helpers";
import VMarkdownEditor from "@/components/utility/markdown.vue";

@Component({
  components: {
    VMarkdownEditor,
  },
})
export default class SubjectDetails extends Vue {
  showDialog = false;
  resolve: ((value: Subject | PromiseLike<Subject>) => void) | undefined;
  reject: ((value: boolean | PromiseLike<boolean>) => void) | undefined;
  errors: Dictionary<string | string[]> = {};
  menu = false;
  subject = Factory.getSubject();

  mounted(): void {
    this.loadSubject();
  }

  @Watch("$route.params.subject_id")
  updateSubject(): void {
    this.loadSubject();
  }

  async loadSubject(): Promise<void> {
    try {
      const subjectId = this.$route.params.subject_id;
      await subjectModule.selectSubject(subjectId);
      if (subjectId) {
        this.subject = subjectModule.getCurrentSubject!;
      }
    } catch (err: any) {
      if (err.response.status == 404) {
        this.$router.replace({ name: "NotFound" });
      }
    }
  }

  get isMenuOpen(): boolean {
    return this.menu;
  }

  async save(): Promise<void> {
    try {
      const subject = await subjectModule.save(this.subject);

      this.errors = {};
      this.showDialog = false;

      if (this.resolve) this.resolve(subject);
      this.$router.push({
        name: "subjectDisplay",
        params: { subject_id: subject.id },
      });
      this.$toast.success(this.$t("global.success").toString());
    } catch (err: any) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error-form").toString());
    }
  }

  close(): void {
    this.errors = {};
    this.showDialog = false;
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
