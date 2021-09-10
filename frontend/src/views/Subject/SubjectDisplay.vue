<template>
  <div v-if="subject">
    <v-row dense>
      <v-col cols="12">
        <v-card flat>
          <v-card-title flat class="header-subject">
            <v-row align="end">
              <v-chip class="ma-2" :color="subject.color" outlined large label>
                <span class="text-h5">{{ subject.name }}</span>
              </v-chip>
            </v-row>
            <v-spacer></v-spacer>
            <v-btn small class="mr-3" :to="{ name: 'subjectEdit' }">
              <v-icon small> mdi-pencil </v-icon>
            </v-btn>
            <v-btn small @click="remove" color="error">
              <v-icon small> mdi-delete </v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text class="pt-2 question-form">
            <span class="text-h6 font-weight-light">{{
              $t("subject.credit")
            }}</span>
            <span class="pl-1 text-h6 font-weight-light">
              {{ subject.ects }}</span
            >
            <div class="text-h5 pt-5">{{ $t("subject.description") }}</div>
            <v-card class="mt-3">
              <div v-if="subject.description">
                <markdown-it-vue
                  class="md-body pa-3"
                  :content="subject.description"
                />
              </div>
            </v-card>
          </v-card-text>
        </v-card>
      </v-col>
      <confirm-modal ref="confirm" />
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Watch, Ref } from "vue-property-decorator";
import MarkdownItVue from "markdown-it-vue";
import subjectModule from "@/store/modules/subjects";
import { Subject } from "@/types/subject";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";

@Component({
  components: {
    MarkdownItVue,
    ConfirmModal,
  },
})
export default class SubjectDisplay extends Vue {
  @Ref() readonly confirm!: ConfirmModal;

  get subject(): Subject | undefined {
    return subjectModule.getCurrentSubject;
  }

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
    } catch (err) {
      if (err.response.status == 404) {
        this.$router.replace({ name: "NotFound" });
      }
    }
  }

  async remove(): Promise<void> {
    const reply = await this.confirm.open();
    if (reply) {
      if (!this.subject) return;
      try {
        await subjectModule.delete(this.subject);
        this.$router.push({ name: "subjects" });
        this.$toast.success(this.$t("global.success").toString());
      } catch (err) {
        this.$toast.error(this.$t("global.errors.unknown").toString());
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.header-subject {
  border-bottom: 1px solid #efefef;
}
</style>
