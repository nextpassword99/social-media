CREATE TABLE t_usuarios (
  usuario_id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  descripcion TEXT,
  pass VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  foto_perfil VARCHAR(255),
  ubicacion VARCHAR(255),
  estado_civil INTEGER,
  educacion VARCHAR(255),
  token TEXT
);

CREATE TABLE t_posts (
  post_id SERIAL PRIMARY KEY,
  usuario_id INTEGER REFERENCES t_usuarios (usuario_id) ON DELETE CASCADE,
  descripcion TEXT NOT NULL,
  fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  tipo TEXT
);

CREATE TABLE t_comentarios (
  comentario_id SERIAL PRIMARY KEY,
  post_id INTEGER REFERENCES t_posts (post_id) ON DELETE CASCADE,
  usuario_id INTEGER REFERENCES t_usuarios (usuario_id) ON DELETE CASCADE,
  contenido TEXT NOT NULL,
  fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE t_likes (
  me_gusta_id SERIAL PRIMARY KEY,
  publicacion_id INTEGER REFERENCES t_posts (post_id) ON DELETE CASCADE,
  usuario_id INTEGER REFERENCES t_usuarios (usuario_id) ON DELETE CASCADE,
  fecha_me_gusta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE t_amigos (
  amistad_id SERIAL PRIMARY KEY,
  usuario_id_1 INTEGER REFERENCES t_usuarios (usuario_id) ON DELETE CASCADE,
  usuario_id_2 INTEGER REFERENCES t_usuarios (usuario_id) ON DELETE CASCADE,
  fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aceptado', 'rechazado')),
  CONSTRAINT t_amistades_usuario_id_1_usuario_id_2_key UNIQUE (usuario_id_1, usuario_id_2)
);

CREATE TABLE t_imagenes (
  imagen_id SERIAL PRIMARY KEY,
  post_id INTEGER REFERENCES t_posts (post_id) ON DELETE CASCADE,
  url_imagen VARCHAR(255) NOT NULL,
  fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE t_videos (
  video_id SERIAL PRIMARY KEY,
  post_id INTEGER REFERENCES t_posts (post_id) ON DELETE CASCADE,
  url_video VARCHAR(255) NOT NULL,
  fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
