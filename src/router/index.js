// Links
// https://wptips.dev/vue-without-webpack/
import { createRouter, createWebHashHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import SettingsView from "../views/SettingsView.vue";
import AboutView from "../views/AboutView.vue";
const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
    path: "/settings",
    name: "settings",
    component: SettingsView,
  },
  {
    path: "/about",
    name: "about",
    component: AboutView,
  },
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
});

router.afterEach((to, from) => {
  let menuRoot = document.querySelector("#toplevel_page_" + wpvue.slug);
  let currentUrl = window.location.href;
  let all = menuRoot.querySelectorAll("a");

  all.forEach((a) => {
    a.parentElement.classList.remove("current");
    if (a.href === currentUrl) {
      a.parentElement.classList.add("current");
    }
  });

});

export default router;
