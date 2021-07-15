<template>
  <v-card flat>
    <v-toolbar flat>
      <v-toolbar-title class="text-h4 font-weight-light">
        {{ $tc("subject.label", subjects.length) }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="success" @click="add" small>{{ $t("global.add") }}</v-btn>
    </v-toolbar>
    <v-card-text v-if="subjects.length > 0">
      <paginate :items="subjects" :perPage="10">
        <template #default="{ items }">
          <v-list>
            <template v-for="(subject, index) in items">
              <v-list-item :key="subject.name">
                <v-list-item-icon>
                  <v-icon :color="subject.color">mdi-square</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title>
                    {{ subject.name }}
                  </v-list-item-title>
                </v-list-item-content>
                <v-list-item-action-text>
                  <v-btn @click="edit(subject)" text color="primary" small>
                    {{ $t("global.edit") }}
                  </v-btn>
                  <v-btn @click="remove(subject)" text color="error" small>
                    {{ $t("global.delete") }}
                  </v-btn>
                </v-list-item-action-text>
              </v-list-item>
              <v-divider
                v-if="index < subjects.length - 1"
                :key="index"
              ></v-divider>
            </template>
          </v-list>
        </template>
      </paginate>
    </v-card-text>
    <v-card-text v-else>
      {{ $t("subject.empty") }}
    </v-card-text>
    <subject-details ref="subjectForm" />
    <confirm-modal ref="confirm" />
  </v-card>
</template>

<script lang="ts">
import subjectModule from "@/store/modules/subjects";
import { Subject } from "@/types/subject";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import Paginate from "@/components/utility/Paginate.vue";
import { Ref, Vue, Component } from "vue-property-decorator";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";

@Component({
  components: {
    SubjectDetails,
    Paginate,
    ConfirmModal,
  },
})
export default class Subjects extends Vue {
  @Ref() readonly subjectForm!: SubjectDetails;
  @Ref() readonly confirm!: ConfirmModal;
  subject = Factory.getSubject();

  get subjects(): Subject[] {
    return subjectModule.subjects;
  }

  async edit(subject: Subject): Promise<void> {
    try {
      await this.subjectForm.open(subject);
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(this.$t("global.error_form").toString());
    }
  }

  async add(): Promise<void> {
    try {
      await this.subjectForm.open();
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(this.$t("global.error_form").toString());
    }
  }

  async remove(subject: Subject): Promise<void> {
    try {
      const reply = await this.confirm.open();
      if (reply) {
        await subjectModule.delete(subject);
        this.$toast.success(this.$t("global.success").toString());
      }
    } catch (err) {
      this.$toast.error(err.response.data);
    }
  }
}
</script>
