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
