//firebase-messaging-sw.js

// Firebase App (the core Firebase SDK) is always required and must be listed first
importScripts('https://www.gstatic.com/firebasejs/8.7.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.7.1/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyB1i9a5ps5_jtRFqIriiTTFcr3JuYVkcLM",
    authDomain: "caravel-f05cb.firebaseapp.com",
    databaseURL: "https://caravel-f05cb-default-rtdb.europe-west1.firebasedatabase.app",
    projectId: "caravel-f05cb",
    storageBucket: "caravel-f05cb.appspot.com",
    messagingSenderId: "84290394883",
    appId: "1:84290394883:web:0c767eb8cb8df524c2e736",
    measurementId: "G-EDQ6Z9C7BW"
}

const app = firebase.initializeApp(firebaseConfig)

const messaging = app.messaging();
