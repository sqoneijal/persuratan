#!/usr/bin/env node

const fs = require("fs");
const path = require("path");

const dir = path.resolve(__dirname, "bundle");

// cek apakah folder bundle ada
if (fs.existsSync(dir)) {
   // baca semua file/folder dalam bundle
   fs.readdirSync(dir).forEach((file) => {
      const filePath = path.join(dir, file);
      // hapus file/folder rekursif
      fs.rmSync(filePath, { recursive: true, force: true });
   });

   console.log("✅ Folder ./bundle berhasil dibersihkan.");
} else {
   console.log("ℹ️ Folder ./bundle tidak ditemukan, skip.");
}
