<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <subject-details :data="subject" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import { Subject } from "@/types/subject";
import subjectModule from "@/store/modules/subjects";

@Component({
  components: {
    SubjectDetails,
  },
})
export default class SubjectCreateOrEdit extends Vue {
  get subject(): Subject {
    if (this.$route.params.subject_id == undefined) return Factory.getSubject();
    const id = this.$route.params.subject_id;
    const originalSubject = subjectModule.getSubject(id);

    if (originalSubject) {
      let subject: Subject = JSON.parse(JSON.stringify(originalSubject));
      return subject;
    } else {
      return Factory.getSubject();
    }
  }
}
</script>
