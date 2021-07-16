<template>
  <v-footer color="primary lighten-3" padless class="mt-10 pt-2">
    <v-card
      flat
      tile
      class="indigo lighten-1 white--text text-center"
      min-width="100%"
    >
      <v-card-text>
        <v-btn
          v-for="link in linksFiltered"
          :key="link"
          class="mx-4 white--text"
          icon
          :href="link.href"
          target="_blank"
        >
          <v-icon size="24px">
            {{ link.icon }}
          </v-icon>
        </v-btn>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-text class="white--text">
        <a class="no-style" href="https://www.he-arc.ch/" target="_blank">
          {{ new Date().getFullYear() }} — <strong>He-Arc, Neuchâtel.</strong>
        </a>
      </v-card-text>
    </v-card>
  </v-footer>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import user from "@/store/modules/user";
import { Dictionary } from "@/types/helpers";

@Component
export default class FooterApp extends Vue {
  links = [
    {
      href: "https://github.com/HE-Arc/Caravel",
      icon: "mdi-github",
      auth: false,
    },
    {
      href: "https://discord.gg/GMMSaKWwZU",
      icon: "mdi-discord",
      auth: true,
    },
  ];

  get linksFiltered(): Array<Dictionary<string | boolean>> {
    return this.links.filter((link) => !link.auth || this.isLoggedIn);
  }
  get isLoggedIn(): boolean {
    return user.isLoggedIn;
  }
}
</script>

<style scoped lang="scss">
a.no-style {
  text-decoration: none;
  color: inherit;
  &:hover {
    opacity: 0.8;
  }
}
</style>
