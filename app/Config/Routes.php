<?php

$routes = service('routes');

function sertifikatKPM($routes): void
{
   $routes->group('sertifikatkpm', function ($routes) {
      $routes->get('cetak/(:num)', 'SertifikatKPM::cetak/$1');

      $routes->post('getdata', 'SertifikatKPM::getData');
   });
}
sertifikatKPM($routes);

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
      $routes->post('khs', 'Sevima::getKhs');
      $routes->post('transkrip', 'Sevima::transkripAkhir');
   });
}

akademik($routes);
function akademik($routes): void
{
   $routes->group('akademik', ['namespace' => 'App\Controllers\Akademik'], function ($routes) {
      akademikSuratAktifKuliah($routes);
      akademikPenelitian($routes);
      akademikMagang($routes);
      akademikTidakMenerimaBeasiswa($routes);
      akademikKHS($routes);
      akademikTranskrip($routes);
   });
}

function akademikTranskrip($routes): void
{
   $routes->group('transkrip', function ($routes) {
      $routes->get('cetak/(:any)', 'Transkrip::cetak/$1');
   });
}

function akademikKHS($routes): void
{
   $routes->group('khs', function ($routes) {
      $routes->get('cetak/(:any)', 'Khs::cetak/$1');
   });
}

function akademikTidakMenerimaBeasiswa($routes): void
{
   $routes->group('tidakmenerimabeasiswa', function ($routes) {
      $routes->get('cetak/(:num)', 'TidakMenerimaBeasiswa::cetak/$1');

      $routes->post('getdata', 'TidakMenerimaBeasiswa::getData');
      $routes->post('submit', 'TidakMenerimaBeasiswa::submit');
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
      $routes->get('initpage', 'SuratAktifKuliah::initPage');
      $routes->get('cetak/(:num)', 'SuratAktifKuliah::cetak/$1');

      $routes->post('status', 'SuratAktifKuliah::status');
      $routes->post('pengajuan', 'SuratAktifKuliah::pengajuan');
   });
}

function role($routes): void
{
   $routes->group('role', function ($routes) {
      $routes->post('islogin', 'Role::isLogin');
   });
}

role($routes);
