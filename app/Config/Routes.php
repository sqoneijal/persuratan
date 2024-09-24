<?php

$routes = service('routes');

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
   });
}

akademik($routes);
function akademik($routes): void
{
   $routes->group('akademik', ['namespace' => 'App\Controllers\Akademik'], function ($routes) {
      $routes->group('surataktifkuliah', function ($routes) {
         $routes->get('cetak/(:num)', 'SuratAktifKuliah::cetak/$1');
      });


      $routes->post('surataktifkuliah', 'SuratAktifKuliah::index');
   });
}
