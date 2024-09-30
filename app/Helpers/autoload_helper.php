<?php

function tanggal_indo(string $tanggal): string
{
   if ($tanggal) {
      $bulanIndonesia = [
         1 => 'Januari',
         2 => 'Februari',
         3 => 'Maret',
         4 => 'April',
         5 => 'Mei',
         6 => 'Juni',
         7 => 'Juli',
         8 => 'Agustus',
         9 => 'September',
         10 => 'Oktober',
         11 => 'November',
         12 => 'Desember'
      ];

      $split_tanggal = explode('-', $tanggal);
      $tahun = $split_tanggal[0];
      $bulan = intval($split_tanggal[1]);
      $tanggal = $split_tanggal[2];

      return "$tanggal $bulanIndonesia[$bulan] $tahun";
   } else {
      return '';
   }
}
