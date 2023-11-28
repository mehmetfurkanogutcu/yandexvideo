<?php

// Eklentinin ön yüzü için gerekli fonksiyonları tanımlar

// Eklentinin videolarını listeleyen fonksiyon
function yandex_video_list() {
  // Eklentinin veritabanında kayıtlı videolarını listeler
  $videos = get_yandex_video_list();

  // Eklentinin videolarını listeleyen HTML kodunu oluşturur
  $html = <<<HTML
    <div class="yandex-video-list">
      <?php foreach ($videos as $video) { ?>
        <div class="yandex-video">
          <iframe src="<?php echo $video->post_content; ?>" width="560" height="315"></iframe>
          <img src="<?php echo $video->post_image; ?>" alt="Video görüntüsü">
        </div>
      <?php } ?>
    </div>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}

// Eklentinin videosunu görüntüleyen fonksiyon
function yandex_video_display($video_url) {
  // Video iframe kodunu alır
  $video_iframe_code = get_yandex_video_iframe_code($video_url);

  // Video görüntüsünü alır
  $video_image = get_yandex_video_image($video_url);

  // Videoyu görüntülemek için HTML kodunu oluşturur
  $html = <<<HTML
    <div class="yandex-video">
      <iframe src="{$video_iframe_code}" width="560" height="315"></iframe>
      <img src="{$video_image}" alt="Video görüntüsü">
    </div>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}

// Eklentinin ön yüzünde videoları listeler
if (is_admin()) {
  return;
}

yandex_video_list();
