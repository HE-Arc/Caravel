import { AxiosStatic } from "axios";
import firebase from "firebase/app";

declare module "vue/types/vue" {
  interface Vue {
    $http: AxiosStatic;
    $messaging: firebase.messaging.Messaging;
  }
}
