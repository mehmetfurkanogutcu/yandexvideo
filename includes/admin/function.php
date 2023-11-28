<?php

// Eklentinin yönetici arayüzü için gerekli fonksiyonları tanımlar

// Eklentinin seçeneklerini işleyen fonksiyon
function yandex_video_options_handler() {
  // Eklentinin seçeneklerini günceller
  update_option("yandex_video_options", $_POST);

  // Eklentinin seçeneklerini başarıyla güncellediğine dair bir mesaj gösterir
  add_action("admin_notices", "yandex_video_options_updated_notice");
}

// Eklentinin seçeneklerini başarıyla güncellediğine dair bir mesaj gösteren fonksiyon
function yandex_video_options_updated_notice() {
  echo '<div class="notice notice-success">
    <p>Yandex Video seçenekleri başarıyla güncellendi.</p>
  </div>';
}

// Eklentinin videolarını listeleyen fonksiyon
function yandex_video_list_handler() {
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
          <th>İşlemler</th>
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
            <td>
              <a href="<?php echo admin_url("admin.php?page=yandex-video-edit&post_id={$video->ID}"); ?>">Düzenle</a>
              <a href="<?php echo wp_nonce_url(admin_url("admin-post.php?action=yandex_video_delete&post_id={$video->ID}"), "yandex_video_delete_{$video->ID}"); ?>">Sil</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}

// Eklentinin videolarını düzenlemek için kullanılan fonksiyon
function yandex_video_edit_handler() {
  // Eklentinin veritabanında kayıtlı videoyu alır
  $video = get_post($_GET["post_id"]);

  // Eklentinin videosunu düzenleyen HTML kodunu oluşturur
  $html = <<<HTML
    <form action="admin-post.php" method="post">
      <input type="hidden" name="action" value="yandex_video_update" />
      <input type="hidden" name="post_id" value="{$video->ID}" />

      <h1>Yandex Video Düzenle</h1>

      <p>
        Eklentinin videosunu düzenleyin.
      </p>

      <h2>Video URL'si</h2>

      <input type="text" name="yandex_video_url" value="{$video->post_content}" />

      <p>
        Eklemek istediğiniz Yandex Video'nun URL'sini buraya girin.
      </p>

      <input type="submit" value="Kaydet" />
    </form>
  HTML;

  // HTML kodunu ekrana yazdırır
  echo $html;
}

// Eklentinin videolarını silme işlemini gerçekleştiren fonksiyon
function yandex_video_delete_handler() {
  // Eklentinin veritabanında kayıtlı videoyu siler
  wp_delete_post($_GET["post_id"]);

  // Eklentinin
