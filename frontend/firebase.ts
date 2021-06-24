//firebase.ts

import firebase from "firebase/app";
import "firebase/firebase-messaging";
import userModule from "@/store/modules/user";

const firebaseConfig = {
  appId: process.env.VUE_APP_FIREBASE_APP_ID,
  measurementId: process.env.VUE_APP_FIREBASE_MEASUREMENT_ID,
  apiKey: process.env.VUE_APP_FIREBASE_API_KEY,
  authDomain: process.env.VUE_APP_FIREBASE_AUTH_DOMAIN,
  storageBucket: process.env.VUE_APP_FIREBASE_STORAGE_BUCKET,
  databaseURL: process.env.VUE_APP_FIREBASE_DB_URL,
  projectId: process.env.VUE_APP_FIREBASE_PROJECT_ID,
  messagingSenderId: process.env.VUE_APP_FIREBASE_MESSAGING_SENDER_ID,
};

firebase.initializeApp(firebaseConfig);

firebase.messaging().onMessage((payload) => {
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: "/android-chrome-144x144.png",
  };

  if (!("Notification" in window)) {
    console.log("This browser does not support system notifications");
  } else if (Notification.permission === "granted") {
    const notification = new Notification(
      notificationTitle,
      notificationOptions
    );
    notification.onclick = function (event) {
      event.preventDefault();
      window.open(payload.notification.click_action, "_blank");
      notification.close();
    };
  }
});

if (userModule.isLoggedIn) {
  firebase
    .messaging()
    .getToken({
      vapidKey: process.env.VUE_APP_FIREBASE_VAPID_KEY,
    })
    .then((token) => {
      userModule.addFcmToken(token);
    })
    .catch((err) => {
      console.log(err);
    });
}

export default firebase.messaging();
