<?php
if ($_GET['ajax']) {
  dolores_grid();
  die();
}

require_once(DOLORES_PATH . '/dlib/assets.php');
require_once(DOLORES_PATH . '/dlib/wp_util/add_paged_class.php');
require_once(DOLORES_PATH . '/dlib/wp_admin/settings/home.php');
require_once(DOLORES_PATH . '/dlib/wp_admin/settings/streaming.php');

get_header();

global $wp_query;

$logo_src = DoloresAssets::get_image_uri('scfn/logo.png');

$video_mp4 = DoloresAssets::get_static_uri('videos/scfn/futebol.mp4');
$video_webm = DoloresAssets::get_static_uri('videos/scfn/futebol.webm');
$hero_src = DoloresAssets::get_image_uri('scfn/hero-futebol.jpg');

if (!$paged || $paged == 1) {
  ?>
  <section class="site-presentation explanation">
    <div class="wrap explanation-wrap">
      <iframe
        width="853"
        height="480"
        src="https://www.youtube.com/embed/XTXlM-F4zpQ?rel=0&amp;showinfo=0"
        frameborder="0"
        allowfullscreen
        >
      </iframe>

      <button
          class="site-presentation-button"
          onclick="DoloresAuthenticator.signIn(null, function() { location.href = '/temas'; })"
          >
        Gostou? Clique aqui para participar!
      </button>
    </div>
  </section>

  <section class="site-hero"
      style="background-image: url('<?php echo $hero_src; ?>');">
    <video class="hero-video" autoplay="autoplay" loop="loop"
        poster="<?php echo $hero_src; ?>">
      <source src="<?php echo $video_mp4; ?>" type="video/mp4" />
      <source src="<?php echo $video_webm; ?>" type="video/webm" />
    </video>
    <div class="hero-logo-container">
      <a href="<?php echo site_url(); ?>" title="Página inicial">
        <img class="hero-logo-image" src="<?php echo $logo_src; ?>" />
      </a>
    </div>
    <button class="hero-button toggle-explanation">
      Entenda
    </button>
  </section>

  <?php
  if (DoloresStreaming::get_active()) {
    $title = esc_html(DoloresStreaming::get_title());
    $youtube_id = DoloresStreaming::get_youtube_id();
    $params = "rel=0&amp;showinfo=0&amp;autoplay=1";
    $url = "//youtube.com/embed/${youtube_id}?${params}";
    ?>
    <section class="site-streaming">
      <div class="page wrap">
        <h2 class="streaming-title"><?php echo $title; ?></h2>
        <iframe
          class="streaming-box"
          src="<?php echo $url; ?>"
          frameborder="0"
          allowfullscreen>
        </iframe>
      </div>
    </section>
    <?php
  }
  ?>

  <section class="home-default-section">
    <div class="wrap">
      <ul class="home-main-grid">
        <li class="home-main-item home-bg-map">
          <a href="/bairros" class="home-main-item-link home-main-item-link-no-alpha">
            <div class="home-main-item-border"></div>
            <div class="home-main-item-wrap">
              <h3 class="home-main-item-title">
                Que mudanças você quer para o seu bairro?
              </h3>
              <button class="home-main-item-action">Participe</button>
            </div>
          </a>
        </li>

        <?php
        $taxonomy = 'tema';
        $slug = DoloresHome::get_tema();
        $term = get_term_by('slug', $slug, $taxonomy);
        $link = get_term_link($term, $taxonomy);
        $image = get_term_meta($term->term_id, 'image', true);
        ?>
        <li
            class="home-main-item"
            style="background-image: url('<?php echo $image; ?>');"
            >
          <a href="<?php echo $link; ?>" class="home-main-item-link">
            <div class="home-main-item-wrap">
              <h3 class="home-main-item-title">
                <?php echo $term->name; ?>
              </h3>
              <p class="home-main-item-explanation">
                E se as decisões fossem nossas?
              </p>
              <button class="home-main-item-action">Participe</button>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <section class="home-ideas">
    <div class="wrap">
      <h2 class="home-title">Ideias em destaque</h2>

      <?php
      $list = DoloresHome::get_ideias();
      $query = new WP_Query(array(
        'orderby' => 'post__in',
        'post__in' => $list,
        'post_type' => 'ideia',
        'posts_per_page' => 3
      ));
      dolores_grid_ideias($query, true);
      ?>

      <div class="home-button-container">
        <a class="home-button" href="/temas">Veja todos os temas</a>
      </div>
    </div>
  </section>

  <section class="home-row">
    <div class="wrap">
      <div class="home-col grid-2">
        <?php
        $args = array_merge($wp_query->query_vars, array(
          'posts_per_page' => 4
        ));
        $query = new WP_Query($args);
        dolores_grid($query);
        ?>
      </div>
      <div class="home-col">
        <?php
        $query = new WP_Query(array(
          'category_name' => 'acoes'
        ));
        $query->the_post();
        list($img_src) = wp_get_attachment_image_src(
          get_post_thumbnail_id($post->ID),
          'bigger'
        );
        $style = "style=\"background-image:url('$img_src');\"";
        ?>
        <div class="home-col-wrap"<?php echo $style; ?>>
          <a class="home-main-item-link" href="<?php the_permalink(); ?>">
            <h4 class="home-action-label">Ação</h4>
            <h2 class="home-action-title">
              <?php the_title(); ?>
            </h2>
            <button class="home-action-button home-main-item-action">
              Vem junto!
            </button>
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php
  $flow1 = DoloresAssets::get_image_uri('scfn/home-flow-1.png');
  $flow2 = DoloresAssets::get_image_uri('scfn/home-flow-2.png');
  $flow3 = DoloresAssets::get_image_uri('scfn/home-flow-3.png');
  ?>

  <section class="home-flow">
    <div class="wrap">
      <ol class="flow-list">
        <li class="home-flow-item bg-pattern-light-purple">
          <a href="/baixe-nossos-materiais" class="flow-link">
            <img class="flow-image" src="<?php echo $flow1; ?>" />
            <div class="flow-item-title-container">
              <h3 class="flow-item-title">
                Baixe nossos materiais
              </h3>
            </div>
          </a>
        </li>
        <li class="home-flow-item bg-pattern-orange">
          <a href="/calendario" class="flow-link">
            <img class="flow-image" src="<?php echo $flow2; ?>" />
            <div class="flow-item-title-container">
              <h3 class="flow-item-title">
                Chegue junto<br />das atividades
              </h3>
            </div>
          </a>
        </li>
        <li class="home-flow-item bg-pattern-teal">
          <a href="/participe" class="flow-link">
            <img class="flow-image" src="<?php echo $flow3; ?>" />
            <div class="flow-item-title-container">
              <h3 class="flow-item-title">
                Participe:<br />seja voluntário
              </h3>
            </div>
          </a>
        </li>
      </ol>
    </div>
  </section>

  <section class="home-row">
    <div class="wrap">
      <div class="home-col">
        <?php
        $query = new WP_Query(array(
          'category_name' => 'apoios',
          'orderby' => 'rand'
        ));
        $query->the_post();
        list($img_src) = wp_get_attachment_image_src(
          get_post_thumbnail_id($post->ID),
          'bigger'
        );
        $style = "style=\"background-image:url('$img_src');\"";
        ?>
        <div class="home-col-wrap"<?php echo $style; ?>>
          <a class="home-main-item-link" href="/secoes/apoios/">
            <h4 class="home-action-label">Quem apoia?</h4>
            <h2 class="home-action-title no-button">
              <?php the_title(); ?>
            </h2>
          </a>
        </div>
      </div>
      <div class="home-col grid-2">
        <?php
        $args = array_merge($wp_query->query_vars, array(
          'posts_per_page' => 4,
          'paged' => 2
        ));
        $query = new WP_Query($args);
        dolores_grid($query);
        ?>
      </div>
    </div>
  </section>

  <section class="site-grid home-grid-negative-margin">
    <div class="wrap">
      <ul></ul>
      <div class="grid-ideias-pagination">
        <a class="grid-ideias-button ajax-load-more" href="/page/2">
          Ver mais
        </a>
      </div>
    </div>
  </section>

  <?php

} else {
  dolores_grid();
}
?>

<?php
get_footer();
?>