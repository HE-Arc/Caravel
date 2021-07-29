import { shallowMount, createLocalVue } from "@vue/test-utils";
import AddContent from "@/components/AddContent.vue";
import router from "@/router";
import Vuetify from "vuetify";
import i18n from "@/i18n";

const localVue = createLocalVue();

describe("AddContent.vue", () => {
  let vuetify: Vuetify;

  beforeEach(() => {
    vuetify = new Vuetify();
  });

  it("Render simple vue", () => {
    const wrapper = shallowMount(AddContent, {
      localVue,
      vuetify,
      router,
      i18n,
    });

    const text = "";
    expect(wrapper.text()).toMatch(text);
  });
});
