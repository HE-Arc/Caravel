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
    <subject-details ref="subjectForm" />
  </div>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Subject } from "@/types/subject";
import { Component, Vue, VModel, Ref } from "vue-property-decorator";
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
  @Ref() readonly subjectForm!: SubjectDetails;

  get subjects(): Dictionary<string | number>[] {
    const subjects: Subject[] = subjectModule.subjects;
    if (!subjects) return [];
    return subjects.map((item: Subject) => ({
      value: item.id.toString(),
      text: item.name,
      color: item.color,
    }));
  }

  async addLabel(text: string): Promise<void> {
    const subjectForm = Factory.getSubject();
    subjectForm.name = text;
    const subject = await this.subjectForm.open(subjectForm);
    this.subject = subject.id.toString();
  }
}
</script>
