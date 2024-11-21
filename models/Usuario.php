<?php
class Usuario
{
  private $id;
  private $nombre;
  private $apellido;
  private $email;
  private $foto_perfil;
  private $descripcion;
  private $ubi;
  private $estado_civil;
  private $fecha_registro;
  private $educacion;

  public function __construct($usuario_id)
  {
    $this->id = $usuario_id;
  }

  public function getUsuarioId()
  {
    return $this->id;
  }

  public function getNombre()
  {
    return $this->nombre;
  }

  public function getApellido()
  {
    return $this->apellido;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getFotoPerfil()
  {
    return $this->foto_perfil;
  }

  public function getDescripcion(): string
  {
    return $this->descripcion;
  }

  public function getUbicacion(): string
  {
    return $this->ubi;
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
