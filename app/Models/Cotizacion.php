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
}