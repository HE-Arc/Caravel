<template>
  <div style="display: contents">
    <v-autocomplete-filter
      :label="$t('task.form.subject.label')"
      :placeholder="$t('task.form.subject.placeholder')"
      :manager="$router.resolve({ name: 'subjects' }).href"
      filled
      :items="subjects"
      menu-props="closeOnContentClick"
      @create="addLabel"
      v-model="subject"
      dense
      :manageName="$t('subject.manage')"
      v-bind="$attrs"
      v-on="$listeners"
    >
      <template v-slot:item="data">
        <v-list-item-icon>
          <v-icon :color="data.item.color">mdi-square</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            {{ data.item.text }}
          </v-list-item-title>
        </v-list-item-content>
      </template>
      <template v-slot:selection="data">
        <v-icon :color="data.item.color" small>mdi-square</v-icon>
        {{ data.item.text }}
      </template>
    </v-autocomplete-filter>
    <subject-details
      :subjectData="subjectModal"
      :isActive="openSubjectForm"
      @close="openSubjectForm = false"
      @handle-subject="handleNewSubject"
    />
  </div>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Subject } from "@/types/subject";
import { Component, Vue, VModel } from "vue-property-decorator";
import subjectModule from "@/store/modules/subjects";
import SubjectDetails from "@/components/subject/SubjectDetails.vue";
import Factory from "@/types/Factory";
import VAutocompleteFilter from "@/components/utility/VAutocompleteFilter.vue";

@Component({
  components: {
    SubjectDetails,
    VAutocompleteFilter,
  },
})
export default class SelectSubject extends Vue {
  @VModel({ type: String }) subject!: string;
  subjectModal: Subject = Factory.getSubject();
  openSubjectForm = false;

  get subjects(): Dictionary<string | number>[] {
    const subjects: Subject[] = subjectModule.subjects;
    if (!subjects) return [];
    return subjects.map((item: Subject) => ({
      value: item.id.toString(),
      text: item.name,
      color: item.color,
    }));
  }

  addLabel(text: string): void {
    this.subjectModal.name = text;
    this.openSubjectForm = true;
  }

  handleNewSubject(subject: Subject): void {
    this.subject = subject.id;
  }
}
</script>
