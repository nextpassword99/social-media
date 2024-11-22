<?php
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../models/Post.php";
require_once __DIR__ . "/../components/PostComponent.php";
require_once __DIR__ . "/../utils/HtmlHelper.php";

class VideosController
{
    private $Usuario;
    private $VideoService;

    public function __construct(Usuario $Usuario, VideoService $VideoService)
    {
        $this->Usuario = $Usuario;
        $this->VideoService = $VideoService;
    }

    public function render()
    {
        $videos = $this->generarPosts();
        $recursos = $this->cargarRecursos();
        return $videos . $recursos;
    }
    public function generarPosts()
    {
        $postObject = new Post($this->usuario_id_session ?? 1);
        $videos = $postObject->getVideosAleatorios();

        $file_post = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
        $file_post_estilos = HtmlHelper::extractStyles($file_post);
        $file_post_sin_estilos = HtmlHelper::removeStyles($file_post);

        $post_html = '';
        foreach ($videos as $video) {
            $post = new Post($video['post_id']);
            $usuario = new Usuario($post->getUsuarioId() ?? 1);
            $postComponent = new PostComponent(
                $this->usuario_id_session ?? 1,
                $this->datos['foto_perfil'],
                $post->getUsuarioId() ?? 1,
                $video['post_id'],
                $usuario->getFotoPerfil(),
                $this->datos['nombre_completo'],
                $usuario->getNombre() . " " . $usuario->getApellido(),
                $post->getFechaPublicacion(),
                $post->getDescripcion() ?? "",
                $file_post_sin_estilos,
                'video',
            );
            $post_html .= $postComponent->render();
        }
        $post_html .= $file_post_estilos;

        return $post_html;
    }

    public function cargarRecursos()
    {
        $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
        $script_comentario = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));

        return $script_comentario . $styles_burbuja;
    }
}
