-- ============================================
-- MIGRACIÓN: 004_create_ajustes
-- FECHA: 2024-01-20
-- ============================================

CREATE TABLE IF NOT EXISTS ajustes (

    idAjuste INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

    remitente VARCHAR(100) NULL,

    mensajePresentacion VARCHAR(512) NULL,

    mensajeAgradecimiento VARCHAR(512) NULL,

    mensajePie VARCHAR(512) NULL

);