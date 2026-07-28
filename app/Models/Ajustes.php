<?php

require_once __DIR__ . '/../../core/Model.php';

class Ajustes extends Model
{

 public function obtener()
    {
        $sql = "SELECT * FROM ajustes LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no hay registros, crear uno por defecto
        if (!$result) {
            $this->crearPorDefecto();
            return $this->obtener();
        }

        return $result;
    }

    public function crearPorDefecto()
    {
        $sql = "
            INSERT INTO ajustes (remitente, mensajePresentacion, mensajeAgradecimiento, mensajePie)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'BlackCore Cotizaciones',
            'Gracias por permitirnos presentar nuestra propuesta de servicios.',
            'Agradecemos su preferencia y confianza en nosotros.',
            'Este documento es una cotización sujeta a cambios sin previo aviso.'
        ]);
    }

    public function actualizar(array $data)
    {
        $sql = "
            UPDATE ajustes
            SET
                remitente = :remitente,
                mensajePresentacion = :mensajePresentacion,
                mensajeAgradecimiento = :mensajeAgradecimiento,
                mensajePie = :mensajePie
            WHERE idAjuste = 1
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':remitente' => $data['remitente'],
            ':mensajePresentacion' => $data['mensajePresentacion'],
            ':mensajeAgradecimiento' => $data['mensajeAgradecimiento'],
            ':mensajePie' => $data['mensajePie']
        ]);
    }
}
