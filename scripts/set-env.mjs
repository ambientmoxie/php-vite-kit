/**
 * This script updates the VITE_DEV and VITE_LOCAL_IP variables
 * in the .env file based on the current npm lifecycle event.
 * If the event is 'dev', VITE_DEV is true, otherwise false.
 */

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import { getLocalIp } from "./get-ip.mjs";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const envFilePath = path.join(__dirname, "../.env");

function readFileContent(filePath) {
  return fs.readFileSync(filePath, "utf-8");
}

function replaceEnvVariable(content, key, value) {
  const regex = new RegExp(`^${key}=.*`, "m");
  return content.match(regex)
    ? content.replace(regex, `${key}=${value}`)
    : `${content.trim()}\n${key}=${value}`;
}

function updateEnvFile(filePath, key, value) {
  const content = readFileContent(filePath);
  const updatedContent = replaceEnvVariable(content, key, value);
  fs.writeFileSync(filePath, updatedContent, "utf-8");
}

// Identify which script was triggered: dev, build, etc.
const scriptName = process.env.npm_lifecycle_event;

// Set VITE_DEV to true only for `dev`, false otherwise
const isDevMode = scriptName === "dev" || scriptName === "host";
updateEnvFile(envFilePath, "VITE_DEV", isDevMode.toString());

// Update the local IP (network testing)
const localIp = getLocalIp();
updateEnvFile(envFilePath, "VITE_LOCAL_IP", localIp);

// Update environment mode variable
updateEnvFile(envFilePath, "VITE_ENV_MODE", scriptName);
