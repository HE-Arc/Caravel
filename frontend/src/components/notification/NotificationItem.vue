<template>
  <v-list-item :key="item.id" :href="href">
    <v-list-item-avatar>
      <v-icon>{{ $t(`notifications.${item.data.type}.icon`) }}</v-icon>
    </v-list-item-avatar>
    <v-list-item-content>
      <v-list-item-title>
        {{ item.data.title }}
      </v-list-item-title>
      <v-list-item-subtitle>
        {{ item.data.message }}
      </v-list-item-subtitle>
    </v-list-item-content>
    <v-list-item-action>
      <v-btn fab small elevation="1" @click.prevent="markAsRead(item)">
        <v-icon small> mdi-email-open </v-icon>
      </v-btn>
    </v-list-item-action>
  </v-list-item>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import userModule from "@/store/modules/user";
import Notification from "@/types/notification";

@Component
export default class NotificationItem extends Vue {
  @Prop() item!: Notification;
  converter = { Task: "taskDisplay" };

  converterTo(name: string): string {
    return name in this.converter ? this.converter[name] : name;
  }

  markAsRead(): void {
    userModule.markAsRead([this.item]);
  }

  get href(): string | undefined {
    if (this.item.data.type == 3) return undefined;
    return this.$router.resolve({
      name: this.converterTo(this.item.data.model),
      params: {
        group_id: this.item.data.group_id,
        task_id: this.item.data.model_id,
      },
    }).href;
  }
}
</script>
