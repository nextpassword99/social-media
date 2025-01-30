insert into
    t_usuarios (
        usuario_id,
        nombre,
        apellido,
        email,
        descripcion,
        pass,
        fecha_registro,
        foto_perfil,
        ubicacion,
        estado_civil,
        educacion,
        token
    ) (
        1,
        'Trodi',
        'Dev',
        'trodi@example.com',
        'Hola, soy Trodi, me gusta la programación y el desarrollo web. Me interesa aspectos relacionados a la tecnología. Publico post de noticias.',
        '$2a$12$/NXfcBrGLHerFxiKejGk7OVtpMJUvpo9kcBHWB4nRfoC0ZtT6zd4u',
        '2024-11-02 19:54:45.789350',
        'https://portal.andina.pe/EDPfotografia3/Thumbnail/2021/08/05/000796433W.jpg',
        'Perú, Cusco',
        1,
        'Ingeniería de Sistemas',
        '06346304af1602e0c11c17fb1d72db023bb87d023e59c39259f6cee03d02d544'
    ),
    (
        2,
        'Alex',
        'Perez',
        'alex@example.com',
        'Entusiasta del diseño gráfico y marketing digital.',
        '$2a$12$/NXfcBrGLHerFxiKejGk7OVtpMJUvpo9kcBHWB4nRfoC0ZtT6zd4u',
        '2024-11-02 19:54:45.789350',
        'https://i.pinimg.com/236x/05/37/a9/0537a9dc95d1a37a3fe450e607c87911.jpg',
        'Perú, Lima',
        2,
        'Diseño Gráfico',
        'bf9e3b7b32f46589a3bd69068038748b7c5cc7c31007b9bcd4e4b1d790827e6b'
    );