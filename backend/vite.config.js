import { defineConfig, preprocessCSS } from "vite";
import laravel from "laravel-vite-plugin";

const path = require("path");

export default defineConfig({
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/scss/app.scss",
                "resources/scss/home.scss",
                "resources/scss/careers.scss",
                "resources/scss/fair-events.scss",
                "resources/scss/contact.scss",
                "resources/scss/projects.scss",
                "resources/scss/company.scss",
                "resources/scss/project-category.scss",
                "resources/scss/project-internal.scss",
                "resources/scss/terms.scss",
                "resources/scss/abby-smart.scss",
                "resources/scss/policy.scss",
                "resources/js/app.js",
                "resources/js/projects.js",
                "resources/js/sub-tags.js",
                "resources/js/project-details.js",
                "resources/js/project-details.js",
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessCSS,
    },
});
