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
        $post_video = $this->VideoService->getVideosAleatorios();

        $file_post = file_get_contents(__DIR__ . '/../views/components/publication/post.html');
        $file_post_estilos = HtmlHelper::extractStyles($file_post);
        $file_post_sin_estilos = HtmlHelper::removeStyles($file_post);
        $file_post_sin_scripts = HtmlHelper::removeScripts($file_post_sin_estilos);

        $post_html = '';
        foreach ($post_video as $post) {
            $postComponent = new PostComponent(
                $this->Usuario->getUsuarioId(),
                $this->Usuario->getFotoPerfil(),
                $post->getUsuarioId(),
                $post->getPostId(),
                $post->getUsuarioFotoPerfil(),
                $this->Usuario->getNombreCompleto(),
                $post->getUsuarioNombreCompleto(),
                $post->getFechaPublicacion(),
                $post->getDescripcion(),
                $post->getIsLike(),
                $post->getLikesCount(),
                $post->getComentariosCount(),
                $post->getComentarios(),
                $post->getImgs(),
                $post->getVideos(),
                $file_post_sin_scripts,
            );
            $post_html .= $postComponent->render();
        }

        return $post_html;
    }

    public function cargarRecursos()
    {
        $styles_burbuja = HtmlHelper::extractStyles(file_get_contents(__DIR__ . '/../views/components/publication/burbuja-comentario.html'));
        $script_comentario = HtmlHelper::extractScripts(file_get_contents(__DIR__ . '/../views/components/publication/contenedor-comentario.html'));

        return $script_comentario . $styles_burbuja;
    }
}
