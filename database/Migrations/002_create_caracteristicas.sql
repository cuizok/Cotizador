-- ============================================
-- MIGRACIÓN: 004_create_caracteristicas
-- FECHA: 2024-01-20
-- ============================================

create table if not exists caracteristicas_cotizaciones (
  id             int not null auto_increment,
  idCotizacion   int null,
  caracteristica varchar(255) null,
  primary key (id),
  foreign key (idCotizacion) references cotizaciones (id)
    on delete cascade
    on update cascade
);
