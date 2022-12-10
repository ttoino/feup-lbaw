import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    publicDir: "public",
    plugins: [
        laravel({
            publicDirectory: "public",
            input: [
                "resources/assets/ts/app.ts",
                "resources/assets/sass/app.scss",
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            "~bootstrap-icons": path.resolve(
                __dirname,
                "node_modules/bootstrap-icons"
            ),
            "./fonts": path.resolve(
                __dirname,
                "node_modules/bootstrap-icons/font/fonts"
            ),
        },
    },
});
