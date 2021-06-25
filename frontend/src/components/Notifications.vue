<template>
  <div>
    <v-menu offset-y :close-on-content-click="false">
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-badge color="red" :content="items.length" v-if="hasItem">
            <v-icon>mdi-bell-ring</v-icon>
          </v-badge>
          <v-icon v-else>mdi-bell-ring</v-icon>
        </v-btn>
      </template>
      <v-card elevation="16" max-width="400" class="mx-auto">
        <v-list three-line v-if="hasItem">
          <v-virtual-scroll
            :items="items"
            height="300"
            item-height="90"
            width="400"
          >
            <template v-slot:default="{ item }">
              <notification-item
                :key="item.id"
                :item="item"
              ></notification-item>
              <v-divider :key="item.id"></v-divider>
            </template>
          </v-virtual-scroll>
        </v-list>
        <v-card-text v-else>
          {{ $t("notifications.empty") }}
        </v-card-text>
        <v-card-actions>
          <v-btn text color="primary">{{ $t("notifications.readAll") }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import userModule from "@/store/modules/user";
import Notification from "@/types/notification";
import NotificationItem from "@/components/notification/NotificationItem.vue";

@Component({
  components: {
    NotificationItem,
  },
})
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
    userModule.markAsRead([notif]);
  }

  markAllAsRead(): void {
    userModule.markAsRead(this.items);
  }

  mounted(): void {
    //first time load all
    userModule.loadNotifications();

    this.$messaging.onMessage(() => {
      userModule.loadNotifications();
    });
  }
}
</script>
