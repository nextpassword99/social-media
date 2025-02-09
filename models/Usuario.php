<?php
class Usuario
{
  private $id;
  private $nombre;
  private $apellido;
  private $email;
  private $foto_perfil;
  private $descripcion;
  private $ubicacion;
  private $estado_civil;
  private $fecha_registro;
  private $educacion;

  public function __construct(
    $usuario_id,
    $nombre,
    $apellido,
    $email,
    $foto_perfil,
    $descripcion,
    $ubicacion,
    $estado_civil,
    $fecha_registro,
    $educacion
  ) {
    $this->id = $usuario_id;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->email = $email;
    $this->foto_perfil = $foto_perfil;
    $this->descripcion = $descripcion;
    $this->ubicacion = $ubicacion;
    $this->estado_civil = $estado_civil;
    $this->fecha_registro = $fecha_registro;
    $this->educacion = $educacion;
  }

  public function getUsuarioId(): int
  {
    return $this->id;
  }

  public function getNombre(): string
  {
    return $this->nombre;
  }

  public function getApellido(): string
  {
    return $this->apellido;
  }

  public function getNombreCompleto(): string
  {
    return $this->nombre . ' ' . $this->apellido;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getFotoPerfil(): string
  {
    return $this->foto_perfil;
  }

  public function getDescripcion(): string
  {
    return $this->descripcion;
  }

  public function getUbicacion(): string
  {
    return $this->ubicacion;
  }

  public function getFecha_registro(): string
  {
    return $this->fecha_registro;
  }

  public function getEstadoCivil(): string
  {
    $estado = $this->estado_civil;
    switch ($estado) {
      case 1:
        return "Soltero";
      case 2:
        return "Casado";
      case 3:
        return "Viudo";
      case 4:
        return "Divorciado";
      default:
        return "Desconocido";
    }
  }

  public function getEducacion(): string
  {
    return $this->educacion;
  }
}
