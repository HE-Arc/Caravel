<template>
  <div>
    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-badge color="red" :content="items.length" v-if="hasItem">
            <v-icon>mdi-bell-ring</v-icon>
          </v-badge>
          <v-icon v-else>mdi-bell-ring</v-icon>
        </v-btn>
      </template>
      <v-card elevation="16" max-width="400" class="mx-auto">
        <v-virtual-scroll
          :items="items"
          height="300"
          item-height="64"
          width="400"
          v-if="hasItem"
        >
          <template v-slot:default="{ item }">
            <v-list-item
              :key="item.id"
              :to="{
                name: converterTo(item.data.model),
                params: {
                  group_id: item.data.group_id,
                  task_id: item.data.model_id,
                },
              }"
              exact
            >
              <v-list-item-action>
                <v-icon>{{
                  $t(`notifications.${item.data.type}.icon`)
                }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.data.title }}
                </v-list-item-title>
                <v-list-item-subtitle>
                  {{ item.data.message }}
                </v-list-item-subtitle>
              </v-list-item-content>
              <v-list-item-action>
                <v-btn fab small elevation="1" @click="markAsRead(item)">
                  <v-icon small> mdi-email-open </v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </template>
        </v-virtual-scroll>
        <v-card-text v-else>
          {{ $t("notifications.empty") }}
        </v-card-text>
      </v-card>
    </v-menu>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import userModule from "@/store/modules/user";
import Notification from "@/types/notification";

@Component
export default class Notifications extends Vue {
  converter = { Task: "taskDisplay" };

  get items(): Notification[] {
    return userModule.notifications;
  }

  get hasItem(): boolean {
    return this.items.length > 0;
  }

  converterTo(name: string): string {
    return name in this.converter ? this.converter[name] : name;
  }

  markAsRead(notif: Notification): void {
    userModule.markAsRead(notif);
  }

  mounted(): void {
    this.$messaging.onMessage(() => {
      userModule.loadNotifications();
    });
  }
}
</script>
