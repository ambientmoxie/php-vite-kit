import { defineConfig } from "vite";
import dotenv from "dotenv";
import fullReload from "vite-plugin-full-reload";

// Load .env variables
dotenv.config();

const isDev = process.env.VITE_ENV_MODE === "dev";
const isHost = process.env.VITE_ENV_MODE === "host";

export default defineConfig({
  // Set base path for built assets (used in production)
  base: isDev || isHost ? "" : `/build/bundle`,
  publicDir: false, // ✅ prevent recursive copying of public/

  server: {
    // Use local IP if host mode, else localhost
    host: isHost ? true : "localhost",
    origin: isHost
      ? `http://${process.env.VITE_LOCAL_IP}:3000`
      : "http://localhost:3000",
    port: 3000,
    hot: true,
  },

  // Reload browser when PHP files change
  plugins: [fullReload(["**/*.php"])],

  build: {
    outDir: "public/build/bundle", // ✅ build output goes here
    // Disable base64 inlining for assets like fonts
    assetsInlineLimit: 0,

    rollupOptions: {
      // Main JS entry point
      input: "./src/js/main.js",

      output: {
        // Hashed output filenames
        entryFileNames: "[name]-[hash].js",
        assetFileNames: "[name]-[hash][extname]",
      },
    },

    manifest: true, // Generates manifest.json for PHP
    emptyOutDir: true, // Clean build folder before building
  },

  css: {
    preprocessorOptions: {
      scss: {
        // Use modern Dart Sass compiler API
        api: "modern-compiler",
      },
    },
  },
});
