<?php

// Eklentinin temel yapısını ve işlevlerini tanımlar

// Eklentinin dosya yapısını tanımlar
define("PLUGIN_DIR", __DIR__);

// Eklentinin adını tanımlar
define("PLUGIN_NAME", "Yandex Video Eklentisi");

// Eklentinin sürümünü tanımlar
define("PLUGIN_VERSION", "1.0");

// Eklentinin yönetici arayüzünü yükler
require_once PLUGIN_DIR . "/includes/admin/admin.php";

// Eklentinin ön yüzünü yükler
require_once PLUGIN_DIR . "/includes/public/index.php";

// Eklentinin yönetici arayüzü için gerekli fonksiyonları tanımlar

// Video URL'sinin geçerli olup olmadığını kontrol eder
function is_valid_url($url) {
  return filter_var($url, FILTER_VALIDATE_URL);
}

// Video iframe kodunu alır
function get_yandex_video_iframe_code($video_url) {
  return file_get_contents("https://yandex.com/video/embed/" . $video_url);
}

// Video görüntüsünü alır
function get_yandex_video_image($video_url) {
  return file_get_contents("https://yandex.com/video/get-image/" . $video_url);
}

// Videoyu veritabanına kaydeder
function save_yandex_video($video_url) {
  // Video URL'sini kontrol et
  if (!is_valid_url($video_url)) {
    return false;
  }

  // Video iframe kodunu ve görüntüsünü al
  $video_iframe_code = get_yandex_video_iframe_code($video_url);
  $video_image = get_yandex_video_image($video_url);

  // Videoyu veritabanına kaydet
  $post_data = [
    "post_title" => "Yandex Video",
    "post_content" => $video_iframe_code,
    "post_status" => "publish",
    "post_type" => "yandex_video",
  ];
  wp_insert_post($post_data);

  // Videoyu seçeneklere kaydet
  update_option("yandex_video_url", $video_url);
  update_option("yandex_video_iframe_code", $video_iframe_code);
  update_option("yandex_video_image", $video_image);

  return true;
}

// Video listesini alır
function get_yandex_video_list() {
  // Veritabanında videoları listelemek için bir sorgu çalıştır
  $query = new WP_Query([
    "post_type" => "yandex_video",
    "post_status" => "publish",
  ]);

  // Video listesini döndür
  return $query->get_posts();
}

// Videoyu ön yüzde görüntüler
function display_yandex_video($video_url) {
  // Video iframe kodunu al
  $video_iframe_code = get_yandex_video_iframe_code($video_url);

  // Video görüntüsünü al
  $video_image = get_yandex_video_image($video_url);

  // Videoyu görüntülemek için HTML kodunu oluştur
  $html = <<<HTML
    <div class="yandex-video">
      <iframe src="{$video_iframe_code}" width="560" height="315"></iframe>
      <img src="{$video_image}" alt="Video görüntüsü">
    </div>
  HTML;

  // HTML kodunu döndür
  return $html;
}
