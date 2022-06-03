<template>
  <v-container>
    <router-view v-if="$route.name != 'subjects'" />
    <div v-else>
      <v-card flat>
        <v-toolbar flat>
          <v-toolbar-title class="text-h4 font-weight-light">
            {{ $tc("subject.label", subjects.length) }}
          </v-toolbar-title>

          <v-spacer></v-spacer>
          <v-btn color="success" :to="{ name: 'newSubject' }" small>{{
            $t("global.add")
          }}</v-btn>
        </v-toolbar>
        <v-row class="pl-8">
          <span class="pt-8">{{ $t("global.sort") }}</span>

          <v-col cols="auto">
            <v-select v-model="sortby" :items="items"> </v-select>
          </v-col>
        </v-row>
        <v-card-text v-if="subjects.length > 0">
          <paginate :items="subjects" :perPage="10">
            <template #default="{ items }">
              <v-list>
                <template v-for="subject in items">
                  <v-list-item
                    :key="subject.name"
                    class="task-item-list mb-2"
                    :to="{
                      name: 'subjectDisplay',
                      params: {
                        subject_id: subject.id,
                      },
                    }"
                  >
                    <v-list-item-icon>
                      <v-icon :color="subject.color">mdi-square</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{ subject.name }}
                      </v-list-item-title>
                    </v-list-item-content>
                    <v-list-item-action-text>
                      <v-btn
                        @click.stop.prevent="remove(subject)"
                        text
                        color="error"
                        small
                      >
                        {{ $t("global.delete") }}
                      </v-btn>
                    </v-list-item-action-text>
                  </v-list-item>
                </template>
              </v-list>
            </template>
          </paginate>
        </v-card-text>
        <v-card-text v-else>
          {{ $t("subject.empty") }}
        </v-card-text>
        <confirm-modal ref="confirm" />
      </v-card>
    </div>
  </v-container>
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
  sortby = "alphabet↓";
  get items(): string[] {
    return ["alphabet↓", "alphabet↑", "date↓", "date↑"];
  }

  get subjects(): Subject[] {
    let subjects = subjectModule.subjects;
    if (this.sortby == "alphabet↑") {
      subjects.sort(function (a, b) {
        return a.name < b.name ? 1 : -1;
      });
      return subjects;
    } else if (this.sortby == "alphabet↓") {
      subjects.sort(function (a, b) {
        return a.name > b.name ? 1 : -1;
      });
      return subjects;
    } else if (this.sortby == "date↑") {
      subjects.sort(function (a, b) {
        return a.created_at < b.created_at ? 1 : -1;
      });
      return subjects;
    } else {
      subjects.sort(function (a, b) {
        return a.created_at > b.created_at ? 1 : -1;
      });
      return subjects;
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
<style lang="scss" scoped>
.task-item-list {
  border: 1px solid #e8e8e8;
  border-radius: 10px;
  cursor: pointer;

  &:hover {
    background-color: WhiteSmoke;
  }
}
</style>
