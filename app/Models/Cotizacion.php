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
            SELECT
                c.*,
                cl.nombre AS cliente,
                cl.empresa,
                cl.correo,
                cl.telefono
            FROM cotizaciones c
            INNER JOIN clientes cl
                ON c.id_cliente = cl.id
            WHERE c.id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id'=>$id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function obtenerDetalles($idCotizacion)
{
    $sql = "
        SELECT
            servicio,
            descripcion,
            costo,
            tiempo,
            unidad_tiempo
        FROM detalle_cotizacion
        WHERE id_cotizacion = :id
        ORDER BY id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ":id"=>$idCotizacion
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function insertarDetalle(
    $idCotizacion,
    array $detalle
    ){

    $sql="

        INSERT INTO detalle_cotizacion(

            id_cotizacion,

            servicio,

            descripcion,

            costo,

            tiempo,

            unidad_tiempo

        )

        VALUES(

            :id_cotizacion,

            :servicio,

            :descripcion,

            :costo,

            :tiempo,

            :unidad

        )

    ";

    $stmt=$this->db->prepare($sql);

    $stmt->execute([

        ":id_cotizacion"=>$idCotizacion,

        ":servicio"=>$detalle["servicio"],

        ":descripcion"=>$detalle["descripcion"],

        ":costo"=>$detalle["costo"],

        ":tiempo"=>$detalle["tiempo"],

        ":unidad"=>$detalle["unidad_tiempo"]

    ]);

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

    public function recalcularTiempoTotal($idCotizacion)
{

    $sql="

        SELECT

        SUM(

            CASE unidad_tiempo

                WHEN 'MINUTOS' THEN tiempo

                WHEN 'HORAS' THEN tiempo*60

                WHEN 'DIAS' THEN tiempo*1440

                WHEN 'SEMANAS' THEN tiempo*10080

                WHEN 'MESES' THEN tiempo*43200

                WHEN 'ANIOS' THEN tiempo*525600

            END

        ) total

        FROM detalle_cotizacion

        WHERE id_cotizacion=:id

    ";

    $stmt=$this->db->prepare($sql);

    $stmt->execute([

        ":id"=>$idCotizacion

    ]);

    $total=$stmt->fetch(PDO::FETCH_ASSOC);

    $sql="

        UPDATE cotizaciones

        SET

        tiempo_total_minutos=:total

        WHERE id=:id

    ";

    $stmt=$this->db->prepare($sql);

    $stmt->execute([

        ":total"=>$total["total"] ?? 0,

        ":id"=>$idCotizacion

    ]);

}
}