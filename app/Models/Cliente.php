<?php

require_once __DIR__ . '/../../core/Model.php';

class Cliente extends Model
{
    public function obtenerTodos()
    {
        $sql = "
            SELECT *
            FROM clientes
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function obtenerClientesActivos()
    {
        $sql = "
            SELECT *
            FROM clientes WHERE estatus = 'ACTIVO'
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerPorId($id)
    {
        $sql = "
            SELECT *
            FROM clientes
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(array $data)
{
    $sql = "
        INSERT INTO clientes
        (
            nombre,
            empresa,
            correo,
            telefono,
            estatus
        )
        VALUES
        (
            :nombre,
            :empresa,
            :correo,
            :telefono,
            :estatus
        )
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':nombre' => $data['nombre'],
        ':empresa' => $data['empresa'] ?? null,
        ':correo' => $data['correo'],
        ':telefono' => $data['telefono'] ?? null,
        ':estatus' => $data['estatus'] ?? 'ACTIVO'
    ]);

    return $this->db->lastInsertId();
}

public function actualizar($id, array $data)
{
    $sql = "
        UPDATE clientes
        SET
            nombre = :nombre,
            empresa = :empresa,
            correo = :correo,
            telefono = :telefono,
            updated_at = NOW()
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre' => $data['nombre'],
        ':empresa' => $data['empresa'],
        ':correo' => $data['correo'],
        ':telefono' => $data['telefono']
    ]);

    return $stmt->rowCount() > 0;
}

public function desactivar($id)
{
    $sql = "
        UPDATE clientes
        SET
            estatus = 'INACTIVO',
            updated_at = NOW()
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->rowCount() > 0;
}

   // ============================================
    // NUEVO MÉTODO PARA CONTAR CLIENTES ACTIVOS
    // ============================================

    public function contarActivos()
    {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE estatus = 'ACTIVO'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Contar clientes por estatus
     */
    public function contarPorEstatus($estatus)
    {
        $sql = "SELECT COUNT(*) as total FROM clientes WHERE estatus = :estatus";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':estatus' => $estatus]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtener estadísticas de clientes
     */
    public function obtenerEstadisticas()
    {
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estatus = 'ACTIVO' THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN estatus = 'INACTIVO' THEN 1 ELSE 0 END) as inactivos
            FROM clientes
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}