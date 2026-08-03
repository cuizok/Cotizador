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
    try {
        $this->db->beginTransaction();

        // 1. Insertar cotización
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

        $idCotizacion = $this->db->lastInsertId();

        // 2. Insertar detalles (servicios)
        if (isset($data['detalles'])) {
            foreach ($data['detalles'] as $detalle) {
                $this->insertarDetalle($idCotizacion, $detalle);
            }
        }

        // 3. Insertar características
        if (isset($data['caracteristicas'])) {
            foreach ($data['caracteristicas'] as $caracteristica) {
                if (!empty($caracteristica['caracteristica'])) {
                    $this->insertarCaracteristica($idCotizacion, $caracteristica['caracteristica']);
                }
            }
        }

        // 4. Recalcular totales
        $costoTotal = $this->recalcularCostoTotal($idCotizacion);
        $tiempoTotalMinutos = $this->recalcularTiempoTotal($idCotizacion);

        // 5. Actualizar totales en la cotización
        $sql = "
            UPDATE cotizaciones
            SET
                costo_total = :costo_total,
                tiempo_total_minutos = :tiempo_total_minutos
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $idCotizacion,
            ':costo_total' => $costoTotal,
            ':tiempo_total_minutos' => $tiempoTotalMinutos
        ]);

        $this->db->commit();

        return $idCotizacion;

    } catch (PDOException $e) {
        $this->db->rollBack();
        error_log("Error al crear cotización: " . $e->getMessage());
        return false;
    }
}

    // NUEVO MÉTODO PARA INSERTAR CARACTERÍSTICAS
public function insertarCaracteristica($idCotizacion, $caracteristica)
{
    $sql = "
        INSERT INTO caracteristicas_cotizaciones
        (
            idCotizacion,
            caracteristica
        )
        VALUES
        (
            :idCotizacion,
            :caracteristica
        )
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':idCotizacion' => $idCotizacion,
        ':caracteristica' => $caracteristica
    ]);
}

// OBTENER CARACTERÍSTICAS DE UNA COTIZACIÓN
public function obtenerCaracteristicas($idCotizacion)
{
    $sql = "
        SELECT id, caracteristica
        FROM caracteristicas_cotizaciones
        WHERE idCotizacion = :idCotizacion
        ORDER BY id
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([':idCotizacion' => $idCotizacion]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    

 public function actualizar($id, array $data) {
    try {
        // Iniciar transacción
        $this->db->beginTransaction();

        // 1. Actualizar los detalles (servicios)
        if (isset($data['detalles']) && is_array($data['detalles'])) {
            // Eliminar detalles existentes
            $sql = "DELETE FROM detalle_cotizacion WHERE id_cotizacion = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            // Insertar nuevos detalles
            foreach ($data['detalles'] as $detalle) {
                $sql = "INSERT INTO detalle_cotizacion 
                        (id_cotizacion, servicio, descripcion, costo, tiempo, unidad_tiempo) 
                        VALUES 
                        (:id_cotizacion, :servicio, :descripcion, :costo, :tiempo, :unidad_tiempo)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':id_cotizacion' => $id,
                    ':servicio' => $detalle['servicio'],
                    ':descripcion' => $detalle['descripcion'] ?? null,
                    ':costo' => $detalle['costo'] ?? 0,
                    ':tiempo' => $detalle['tiempo'] ?? 0,
                    ':unidad_tiempo' => $detalle['unidad_tiempo'] ?? 'HORAS'
                ]);
            }
        }

        // ✅ 2. Actualizar características
        if (isset($data['caracteristicas']) && is_array($data['caracteristicas'])) {
            // Eliminar características existentes
            $sql = "DELETE FROM caracteristicas_cotizaciones WHERE idCotizacion = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            // Insertar nuevas características (solo las que no estén vacías)
            foreach ($data['caracteristicas'] as $caracteristica) {
                if (!empty($caracteristica['caracteristica'])) {
                    $sql = "INSERT INTO caracteristicas_cotizaciones 
                            (idCotizacion, caracteristica) 
                            VALUES 
                            (:idCotizacion, :caracteristica)";
                    
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':idCotizacion' => $id,
                        ':caracteristica' => $caracteristica['caracteristica']
                    ]);
                }
            }
        }

        // 3. Recalcular totales automáticamente
        $costoTotal = $this->recalcularCostoTotal($id);
        $tiempoTotalMinutos = $this->recalcularTiempoTotal($id);

        // 4. Actualizar la cotización - SIEMPRE BORRADOR
        $sql = "
            UPDATE cotizaciones
            SET
                id_cliente = :id_cliente,
                titulo = :titulo,
                descripcion = :descripcion,
                estatus = 'BORRADOR',
                costo_total = :costo_total,
                tiempo_total_minutos = :tiempo_total_minutos,
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':id_cliente' => $data['id_cliente'],
            ':titulo' => $data['titulo'],
            ':descripcion' => $data['descripcion'] ?? null,
            ':costo_total' => $costoTotal,
            ':tiempo_total_minutos' => $tiempoTotalMinutos
        ]);

        // Confirmar transacción
        $this->db->commit();

        return true;

    } catch (PDOException $e) {
        // Revertir en caso de error
        $this->db->rollBack();
        error_log("Error al actualizar cotización: " . $e->getMessage());
        return false;
    }
}

     public function recalcularCostoTotal($idCotizacion) {
        $sql = "
            SELECT COALESCE(SUM(costo), 0) AS total
            FROM detalle_cotizacion
            WHERE id_cotizacion = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idCotizacion]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
     /**
     * Recalcular el tiempo total en minutos de una cotización
     */
    public function recalcularTiempoTotal($idCotizacion) {
        $sql = "
            SELECT
                SUM(
                    CASE unidad_tiempo
                        WHEN 'MINUTOS' THEN tiempo
                        WHEN 'HORAS' THEN tiempo * 60
                        WHEN 'DIAS' THEN tiempo * 1440
                        WHEN 'SEMANAS' THEN tiempo * 10080
                        WHEN 'MESES' THEN tiempo * 43200
                        WHEN 'ANIOS' THEN tiempo * 525600
                        ELSE 0
                    END
                ) AS total
            FROM detalle_cotizacion
            WHERE id_cotizacion = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idCotizacion]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
}