import Vue from "vue";
import Vuetify from "vuetify/lib/framework";
import colors from "vuetify/lib/util/colors";
import en from "vuetify/src/locale/en"; // English
import fr from "vuetify/src/locale/fr"; // fr

Vue.use(Vuetify);

export default new Vuetify({
  theme: {
    dark: false,
    themes: {
      light: {
        primary: "#5e72e4",
        secondary: colors.blueGrey,
      },
      dark: {
        primary: "#5e72e4",
        secondary: colors.blueGrey,
      },
    },
  },
  lang: {
    locales: { fr, en },
    current: "fr",
  },
});
