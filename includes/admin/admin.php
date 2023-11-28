<?php

// Eklentinin yönetici arayüzünü yükler
require_once PLUGIN_DIR . "/includes/admin/functions.php";

// Eklentinin yönetici arayüzüne ait sayfaları ve menüleri ekler
function yandex_video_admin_init() {
  // Eklentinin ayarlar sayfasını ekler
  add_options_page(
    "Yandex Video Eklentisi",
    "Yandex Video",
    "manage_options",
    "yandex-video",
    "yandex_video_options_page"
  );

  // Eklentinin videoları listeleme sayfasını ekler
  add_menu_page(
    "Yandex Videoları",
    "Yandex Videoları",
    "edit_posts",
    "yandex-video-list",
    "yandex_video_list_page"
  );
}

// Eklentinin yönetici arayüzünü yükler
add_action("admin_init", "yandex_video_admin_init");

// Eklentinin ayarlar sayfasını işleyen fonksiyon
function yandex_video_options_page() {
  // Eklentinin seçeneklerini ayarlardan alır
  $options = get_option("yandex_video_options");

  // Eklentinin seçeneklerini işleyen HTML kodunu oluşturur
  $html = <<<HTML
    <form action="options.php" method="post">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page" value="yandex-video" />

      <h1>Yandex Video Eklentisi</h1>

      <p>
        Yandex Video Eklentisi ile sitenize Yandex Videoları ekleyebilirsiniz.
      </p>

      <h2>Video URL'si</h2>

      <input type="text" name="yandex_video_url" value="{$options["yandex_video_url"]}" />

      <p>
        Eklemek istediğiniz Yandex Video'nun URL'sini buraya girin.
      </p>

      <input type="submit" value="Kaydet" />
    </form>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}

// Eklentinin videoları listeleme sayfasını işleyen fonksiyon
function yandex_video_list_page() {
  // Eklentinin veritabanında kayıtlı videolarını listeler
  $videos = get_yandex_video_list();

  // Eklentinin videolarını listeleyen HTML kodunu oluşturur
  $html = <<<HTML
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th>Video URL'si</th>
          <th>Video İframe Kodu</th>
          <th>Video Görüntüsü</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($videos as $video) { ?>
          <tr>
            <td>
              <a href="<?php echo $video->post_url; ?>"><?php echo $video->post_title; ?></a>
            </td>
            <td>
              <?php echo $video->post_content; ?>
            </td>
            <td>
              <img src="<?php echo $video->post_image; ?>" alt="Video görüntüsü">
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}
