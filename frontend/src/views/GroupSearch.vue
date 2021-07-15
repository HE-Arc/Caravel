<template>
  <v-container class="mt-5">
    <v-row no-gutters>
      <v-col cols="12" md="10" offset-md="1" align="center">
        <v-card flat>
          <v-card-title class="text-h4 font-weight-light">
            {{ $t("groups.search-title") }}
          </v-card-title>
          <v-spacer></v-spacer>
          <v-card-text>
            <v-text-field
              v-model.lazy="filters.text"
              :label="$t('groups.search-input')"
              :placeholder="$t('groups.search-type')"
              prepend-icon="mdi-magnify"
              clearable
              autocomplete="off"
            >
              <template #append-outer>
                <v-btn
                  :disabled="!filters.text"
                  color="success"
                  small
                  :to="{
                    name: 'GroupNew',
                    query: { text: filters.text },
                  }"
                >
                  {{ $t("global.add") }}</v-btn
                >
              </template>
            </v-text-field>
          </v-card-text>
          <v-divider dark></v-divider>
          <v-expand-transition>
            <v-container fluid class="white darken-3 pa0" v-if="!!results">
              <v-progress-circular
                :size="70"
                :width="7"
                color="purple"
                indeterminate
                v-if="isLoading"
              ></v-progress-circular>

              <h4 v-else-if="hasNoResult">
                <i18n
                  path="groups.no-results"
                  tag="label"
                  for="groups.no-results-link"
                >
                  <router-link
                    :to="{
                      name: 'GroupNew',
                      query: { text: filters.text },
                    }"
                    >{{ $t("groups.create-link", [filters.text]) }}</router-link
                  >
                </i18n>
              </h4>
              <v-row dense v-else>
                <v-col v-for="item in results" :key="item.id" cols="12" md="6">
                  <group-item :group="item"></group-item>
                </v-col>
                <v-col cols="12">
                  <v-pagination
                    class="mt-4"
                    :value="currentPage"
                    @input="changePage"
                    circle
                    :length="pages"
                    v-if="pages > 1"
                  ></v-pagination>
                </v-col>
              </v-row>
            </v-container>
          </v-expand-transition>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Group } from "@/types/group";
import { Watch } from "vue-property-decorator";
import Vue from "vue";
import Component from "vue-class-component";
import axios from "axios";
import GroupItem from "@/components/GroupItem.vue";
import { Dictionary } from "node_modules/vue-router/types/router";

@Component({
  components: {
    GroupItem,
  },
})
export default class GroupSearch extends Vue {
  results: Group[] = [];
  isLoading = false;
  pages = 0;
  enableWatcher = false;
  filters: Dictionary<string | (string | null)[] | null | undefined> = {
    text: "",
    page: "",
  };
  delayTimer = 0;

  mounted(): void {
    this.filters = Object.assign({}, this.filters, this.$route.query);

    this.enableWatcher = true;
  }

  get hasNoResult(): boolean {
    return !!this.filters.text && this.results.length == 0;
  }

  get params(): Dictionary<string | (string | null)[] | null | undefined> {
    return Object.entries(this.filters).reduce(
      (a: Dictionary<string | (string | null)[] | null | undefined>, [k, v]) =>
        v == null || v == "" ? a : ((a[k] = v), a),
      {}
    );
  }

  get currentPage(): number {
    let page: string = this.filters.page as string;
    return !page || page == "" ? 1 : parseInt(this.filters.page as string);
  }

  @Watch("filters", { deep: true })
  onPropertyChange(): void {
    if (!this.enableWatcher) return;

    clearTimeout(this.delayTimer);
    this.delayTimer = setTimeout(() => {
      this.isLoading = true;
      this.loadData();
    }, 500); // 0.5 sec delay
  }

  changePage(page: number): void {
    let pageStr = page.toString();
    if (this.filters.page != pageStr) {
      this.filters.page = pageStr;
    }
  }

  createGroup(): void {
    this.$router.push({ name: "GroupNew", query: { text: this.filters.text } });
  }

  loadData(): void {
    let queries = this.$route.query;

    if (JSON.stringify(queries) !== JSON.stringify(this.params)) {
      this.$router.replace({ query: this.params });
    }

    axios({
      url: process.env.VUE_APP_API_BASE_URL + "groups",
      params: this.filters,
      method: "GET",
    })
      .then((response) => {
        const groups: Group[] = response.data.data;
        this.results = groups;
        this.pages = response.data.last_page;
      })
      .catch((err) => {
        this.$toast.error(err.response.data.message);
      })
      .finally(() => {
        this.isLoading = false;
      });
  }

  clear(): void {
    this.enableWatcher = false;
    this.results = [];
    this.filters = {
      text: "",
      page: "",
    };
    this.pages = 0;
    this.enableWatcher = true;
  }
}
</script>
