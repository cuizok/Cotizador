<?php

require_once __DIR__ . '/../../core/Model.php';

class Cotizacion extends Model
{
    public function obtenerTodas()
    {
        $sql = "
            SELECT
                c.*,
                cl.nombre AS cliente
            FROM cotizaciones c
            INNER JOIN clientes cl
                ON cl.id = c.id_cliente
            ORDER BY c.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = "
            SELECT *
            FROM cotizaciones
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(array $data)
    {
        $sql = "
            INSERT INTO cotizaciones
            (
                id_cliente,
                titulo,
                descripcion
            )
            VALUES
            (
                :id_cliente,
                :titulo,
                :descripcion
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_cliente' => $data['id_cliente'],
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion']
        ]);

        return $this->db->lastInsertId();
    }
    

    public function actualizar($id, array $data)
    {
        $sql = "
            UPDATE cotizaciones
            SET
                id_cliente = :id_cliente,
                titulo = :titulo,
                descripcion = :descripcion,
                estatus = :estatus,
                costo_total = :costo_total,
                tiempo_total_minutos = :tiempo_total_minutos,
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            'id_cliente' => $data['id_cliente'],
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'],
            ':estatus' => $data['estatus'],
            ':costo_total' => $data['costo_total'],
            ':tiempo_total_minutos' => $data['tiempo_total_minutos']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function recalcularCostoTotal($idCotizacion)
    {
        // Obtener la suma de todos los costos
        $sql = "
            SELECT COALESCE(SUM(costo), 0) AS total
            FROM detalle_cotizacion
            WHERE id_cotizacion = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $idCotizacion
        ]);

        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Actualizar la cotización
        $sql = "
            UPDATE cotizaciones
            SET
                costo_total = :total,
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':total' => $total,
            ':id' => $idCotizacion
        ]);
    }
}