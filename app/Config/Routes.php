<?php

$routes = service('routes');

$routes->get('absen', 'Absen::index');

$routes->group('mahasiswa', function ($routes) {
   $routes->get('/', 'Mahasiswa::index');
   $routes->get('(:any)', 'Mahasiswa::detail/$1');
});

sevima($routes);
function sevima($routes): void
{
   $routes->group('sevima', function ($routes) {
      $routes->get('periodeaktif', 'Sevima::getPeriodeAktif');
      $routes->get('biodata/(:any)', 'Sevima::getDetailBiodata/$1');

      $routes->post('statuspembayaranspp', 'Sevima::getStatusPembayaranSPP');
   });
}

akademik($routes);
function akademik($routes): void
{
   $routes->group('akademik', ['namespace' => 'App\Controllers\Akademik'], function ($routes) {
      akademikSuratAktifKuliah($routes);
      akademikPenelitian($routes);
      akademikMagang($routes);
   });
}

function akademikMagang($routes): void
{
   $routes->group('magang', function ($routes) {
      $routes->get('cetak/(:num)', 'Magang::cetak/$1');

      $routes->post('getdata', 'Magang::getData');
      $routes->post('submit', 'Magang::submit');
   });
}

function akademikPenelitian($routes): void
{
   $routes->group('penelitian', function ($routes) {
      $routes->get('cetak/(:num)', 'Penelitian::cetak/$1');

      $routes->post('status', 'Penelitian::status');
      $routes->post('submit', 'Penelitian::submit');
   });
}

function akademikSuratAktifKuliah($routes): void
{
   $routes->group('surataktifkuliah', function ($routes) {
      $routes->get('cetak/(:num)', 'SuratAktifKuliah::cetak/$1');

      $routes->post('status', 'SuratAktifKuliah::status');
      $routes->post('pengajuan', 'SuratAktifKuliah::pengajuan');
   });
}
