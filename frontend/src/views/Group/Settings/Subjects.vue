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
      <v-list>
        <template v-for="(subject, index) in subjects">
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
    </v-card-text>
    <v-card-text v-else>
      {{ $t("group.request.empty") }}
    </v-card-text>
    <subject-details
      :subjectData="subject"
      :isActive="openSubjectForm"
      @close="openSubjectForm = false"
    />
  </v-card>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import subjectModule from "@/store/modules/subjects";
import { Subject } from "@/types/subject";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";

@Component({
  components: {
    SubjectDetails,
  },
})
export default class Subjects extends Vue {
  openSubjectForm = false;

  subject = Factory.getSubject();

  get subjects(): Subject[] {
    return subjectModule.subjects;
  }

  edit(subject: Subject): void {
    this.subject = subject;
    this.openSubjectForm = true;
  }

  add(): void {
    this.subject = Factory.getSubject();
    this.openSubjectForm = true;
  }

  async remove(subject: Subject): Promise<void> {
    try {
      await subjectModule.delete(subject);
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(err.response.data);
    }
  }
}
</script>
