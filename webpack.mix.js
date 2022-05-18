let mix = require("laravel-mix");
const path = require("path");
const tailwindcss = require("tailwindcss");
require("laravel-mix-blade-reload");

mix.setPublicPath("public");

mix
  .js("src/app.js", "public")
  .vue({ version: 3 })
  .sass("src/assets/app.scss", "public")
  .options({
    processCssUrls: false,
    postCss: [tailwindcss("./tailwind.config.js")],
  });

mix.copy("src/assets/**/*.jpg", "public/images");
mix.copy("src/assets/**/*.jpeg", "public/images");
mix.copy("src/assets/**/*.png", "public/images");
mix.copy("src/assets/**/*.svg", "public/images");

mix.bladeReload({
  path: ["./*.php", "public/*.php"],
});

mix.alias({
  "@": path.join(__dirname, "src"),
});

mix.version();