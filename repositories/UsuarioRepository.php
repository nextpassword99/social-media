<?php
class UsuarioRepository
{
  private $db;
  public function __construct(DB $db)
  {
    $this->db = $db;
  }

}
