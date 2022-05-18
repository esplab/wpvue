import { createApp } from "vue";
import App from "@/App.vue";
import router from "@/router";
import { mix } from "@/lib.js";

const app = createApp(App);
app.use(router).mount(wpvue.mountpoint);

app.config.globalProperties.mix = mix;
