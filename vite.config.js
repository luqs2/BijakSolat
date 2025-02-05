import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.js",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  server: {
    host: "0.0.0.0", // Allows access from external devices
    port: 5173, // Make sure this port is open on your server
    strictPort: true,
  },
});
