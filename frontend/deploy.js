#!/usr/bin/env node

const fs = require("fs");
const path = require("path");
const crypto = require("crypto");
const { execSync } = require("child_process");

const bundleDir = path.resolve(__dirname, "bundle");
const indexSample = path.resolve(__dirname, "index-sample.html");
const indexHtml = path.resolve(__dirname, "index.html");

// 🔹 Generate SHA1 hash dari file
function sha1File(filePath) {
   const buffer = fs.readFileSync(filePath);
   return crypto.createHash("sha1").update(buffer).digest("hex");
}

// 🔹 Hash untuk JS dan CSS
const jsPath = path.join(bundleDir, "App.js");
const cssPath = path.join(bundleDir, "App.css");

const hashJs = sha1File(jsPath);
const hashCss = sha1File(cssPath);

// 🔹 Copy dengan nama baru
const newJs = path.join(bundleDir, `app.${hashJs}.js`);
const newCss = path.join(bundleDir, `app.${hashCss}.css`);

fs.copyFileSync(jsPath, newJs);
fs.copyFileSync(cssPath, newCss);

// 🔹 Hapus file lama
fs.rmSync(jsPath);
fs.rmSync(cssPath);

console.log("✅ File bundle diberi hash:", { newJs, newCss });

// 🔹 SSH ke server & hapus folder bundle lama
execSync(`ssh root@192.168.176.16 "cd /var/www/html/mael/frontend && sudo rm -rf bundle"`, { stdio: "inherit" });

// 🔹 SCP upload bundle
execSync(`scp -r bundle root@192.168.176.16:/var/www/html/mael/frontend`, { stdio: "inherit" });

// 🔹 Copy index-sample.html → index.html
fs.copyFileSync(indexSample, indexHtml);

// 🔹 Update index.html sesuai hash
let html = fs.readFileSync(indexHtml, "utf8");
html = html.replace(`<link rel="stylesheet" href="/bundle/App.css" />`, `<link rel="stylesheet" href="/bundle/app.${hashCss}.css" />`);
html = html.replace(`<script src="/bundle/App.js" type="module"></script>`, `<script src="/bundle/app.${hashJs}.js" type="module"></script>`);

fs.writeFileSync(indexHtml, html);

console.log("✅ index.html diperbarui");

// 🔹 SCP upload index.html
execSync(`scp index.html root@192.168.176.16:/var/www/html/mael/frontend`, { stdio: "inherit" });

console.log("🚀 Deploy selesai!");
