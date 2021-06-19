<template>
  <v-row>
    <v-col cols="12">
      <v-text-field
        :label="$t('search.label')"
        prepend-inner-icon="mdi-magnify"
        dense
        hide-details
        clearable
        solo-inverted
        flat
        color="#5e72e4"
        background-color="#5e72e422"
        class="elevation-0 search-bar"
        autocomplete="off"
        v-model="filters.text"
      >
        <template v-slot:append-outer>
          <slot></slot>
        </template>
      </v-text-field>
    </v-col>
    <v-col cols="12" v-if="hasFilter" class="d-flex align-center">
      <select-member
        dense
        solo
        class="fit"
        v-model="filters.author"
        clearable
        hide-details
      ></select-member>
      <select-type
        v-model="filters.type"
        dense
        solo
        clearable
        class="fit"
        hide-details
      ></select-type>
      <select-subject
        dense
        solo
        class="fit"
        v-model="filters.subject"
        :createNoData="false"
        clearable
        hide-details
      ></select-subject>
      <select-state
        dense
        solo
        class="fit"
        v-model="filters.isOpen"
        clearable
        hide-details
      />
      <v-checkbox
        :value="filters.isPrivate"
        @change="updatePrivate"
        :label="$t('inputs.private.label')"
      ></v-checkbox>
      <v-spacer></v-spacer>
      <v-btn @click="resetSearch" color="error" text v-if="!isEmpty">
        <v-icon left>mdi-close-box</v-icon>{{ $t("global.reset") }}</v-btn
      >
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Prop, Component, Vue, Watch, Emit } from "vue-property-decorator";
import FilterList from "@/components/filters/FilterList.vue";
import groupModule from "@/store/modules/groups";
import SelectType from "@/components/inputs/SelectType.vue";
import SelectSubject from "@/components/inputs/SelectSubject.vue";
import SelectMember from "@/components/inputs/SelectMember.vue";
import SelectState from "@/components/inputs/SelectState.vue";
import { Task } from "@/types/task";
import axios from "axios";

@Component({
  components: {
    FilterList,
    SelectType,
    SelectSubject,
    SelectMember,
    SelectState,
  },
})
export default class SearchBar extends Vue {
  @Prop({ default: false }) hasFilter?: boolean;
  delayTimer = 0;
  isLoading = false;
  enableWatcher = false;

  get isEmpty(): boolean {
    let params = this.params;
    if (params.isOpen == "1") {
      delete params.isOpen;
    }
    return Object.keys(this.params).length == 0;
  }

  filters: Dictionary<string> = {
    isOpen: "1",
  };

  get params(): Dictionary<string | (string | null)[] | null | undefined> {
    return Object.entries(this.filters).reduce(
      (a: Dictionary<string | (string | null)[] | null | undefined>, [k, v]) =>
        v == null || v == "" ? a : ((a[k] = v), a),
      {}
    );
  }

  resetSearch(): void {
    this.filters = {
      isOpen: "1",
    };
  }

  updatePrivate(value: boolean | null): void {
    Vue.set(this.filters, "isPrivate", value ? "1" : "0");
  }

  mounted(): void {
    if (Object.keys(this.$route.query).length !== 0) this.enableWatcher = true;
    this.filters = Object.assign({}, this.filters, this.$route.query);
  }

  @Watch("filters", { deep: true })
  onPropertyChange(): void {
    if (!this.enableWatcher) {
      this.enableWatcher = true;
      return;
    }

    clearTimeout(this.delayTimer);
    this.delayTimer = setTimeout(() => {
      this.$router.replace({ query: this.params });
      this.loadData();
    }, 500); // 0.5 sec delay
  }

  loadData(): void {
    const groupId = groupModule.selectedId;
    this.isLoading = true;
    axios({
      url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks`,
      params: this.filters,
      method: "GET",
    })
      .then((response) => {
        const tasks: Task[] = response.data;
        if (tasks) this.handleTasks(tasks);
      })
      .catch((err) => {
        this.$toast.error(err.response.data.message);
      })
      .finally(() => {
        this.isLoading = false;
      });
  }

  @Emit()
  handleTasks(tasks: Task[]): Task[] {
    return tasks;
  }
}
</script>

<style lang="scss" scoped>
.search-bar::v-deep {
  .v-icon,
  .v-text-field__slot .v-label,
  input {
    color: #5e72e4 !important;
  }
}

.search-bar::v-deep .v-input__append-outer {
  margin-top: 4px !important;
  //margin-left: 30px;
}

.v-select.fit,
.fit::v-deep .v-select {
  margin-right: 5px;
  //max-width: 200px;
  float: left;
  width: min-content;
  min-width: 150px;
}
.v-select.fit::v-deep .v-select__selections,
.fit::v-deep .v-select .v-select__selections {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
