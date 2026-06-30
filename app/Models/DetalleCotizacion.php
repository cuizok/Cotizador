<?php

require_once __DIR__ . '/../../core/Model.php';

class DetalleCotizacion extends Model
{
    public function crear(array $data)
    {
        $sql = "
            INSERT INTO detalle_cotizacion
            (
                id_cotizacion,
                servicio,
                descripcion,
                costo,
                tiempo,
                unidad_tiempo

            )
            VALUES
            (
                :id_cotizacion,
                :servicio,
                :descripcion,
                :costo,
                :tiempo,
                :unidad_tiempo
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_cotizacion' => $data['id_cotizacion'],
            ':servicio' => $data['servicio'],
            ':descripcion' => $data['descripcion'],
            ':costo' => $data['costo'],
            ':tiempo' => $data['tiempo'],
            ':unidad_tiempo' => $data['unidad_tiempo']


        ]);

        return $this->db->lastInsertId();
    }
    
       public function obtenerPorId($id_cotizacion)
    {
        $sql = "
            SELECT *
            FROM detalle_cotizacion
            WHERE id_cotizacion = :id_cotizacion
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_cotizacion' => $id_cotizacion
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        public function actualizar($id_cotizacion, array $data)
    {
        $sql = "
            UPDATE detalle_cotizacion
            SET
                servicio = :servicio,
                descripcion = :descripcion,
                costo = :costo,
                tiempo = :tiempo,
                unidad_tiempo = :unidad_tiempo,
                updated_at = NOW()
            WHERE id_cotizacion = :id_cotizacion
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_cotizacion' => $id_cotizacion,
            ':servicio' => $data['servicio'],
            ':descripcion' => $data['descripcion'],
            ':costo' => $data['costo'],
            ':tiempo' => $data['tiempo'],
            ':unidad_tiempo' => $data['unidad_tiempo'],

        ]);

        return $stmt->rowCount() > 0;
    }

    public function eliminar($id)
    {
        $sql = "
            DELETE
            FROM detalle_cotizacion
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }

}