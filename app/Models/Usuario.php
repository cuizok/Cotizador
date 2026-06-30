<?php

require_once __DIR__ . '/../../core/Model.php';

class Usuario extends Model
{
    public function buscarPorCorreo($correo)
    {
        $sql = "
            SELECT *
            FROM usuarios
            WHERE correo = :correo
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':correo' => $correo
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}