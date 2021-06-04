import Vue from "vue";
import Vuetify from "vuetify/lib/framework";
import colors from "vuetify/lib/util/colors";

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
});
