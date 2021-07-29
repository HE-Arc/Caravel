<template>
  <v-select
    :items="members"
    :label="$t('inputs.selectmember.label')"
    :placeholder="$t('inputs.selectmember.placeholder')"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template
      :slot="slotName"
      v-for="slotName in ['item', 'selection']"
      slot-scope="props"
    >
      <v-avatar class="mr-1" color="primary" size="20" :key="slotName">
        <v-img
          v-if="props.item.picture"
          :alt="`${props.item.text} avatar`"
          :src="props.item.picture"
        ></v-img>
        <span v-else class="white--text text-caption">
          {{ props.item.text | initials }}
        </span>
      </v-avatar>
      {{ props.item.text }}
    </template>
  </v-select>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue } from "vue-property-decorator";
import memberModule from "@/store/modules/members";
import { Member } from "@/types/member";

@Component
export default class SelectMember extends Vue {
  get members(): Dictionary<string>[] {
    const mbrs: Member[] = memberModule.accepted;
    if (!mbrs) return [];
    return mbrs.map((item: Member) => ({
      value: item.id.toString(),
      text: item.name,
      picture: item.picture,
    }));
  }
}
</script>
