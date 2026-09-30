<footer class="site-footer">
  <div class="container">
    <div class="footer-inner">
      
      <?php /* Sisi Kiri: Logo Footer + Social Media di bawahnya */ ?>
      <div class="footer-left">
        <div class="footer-brand">
          <?php 
            $footerSlot = $site['logo_placements']['footer'] ?? null;
            $footerSlotActive = !isset($footerSlot['is_active']) || !empty($footerSlot['is_active']);
            $footerVariant = $footerSlot['variant'] ?? 'white';

            $footerLogoUrl = !empty($site['logo_footer_target_url']) ? $site['logo_footer_target_url'] : (!empty($site['logo_b_target_url']) ? $site['logo_b_target_url'] : 'https://investor.id');
            
            if ($footerVariant === 'color') {
                $footerLogo = !empty($site['logo_footer']) ? $site['logo_footer'] : (!empty($site['logo_b_color']) ? $site['logo_b_color'] : base_url('assets/img/logo_id_color.webp'));
            } else {
                $footerLogo = !empty($site['logo_footer_white']) ? $site['logo_footer_white'] : (!empty($site['logo_footer']) ? $site['logo_footer'] : base_url('assets/img/logo_id_white.webp'));
            }
          ?>
          <?php if ($footerSlotActive): ?>
            <a href="<?= esc($footerLogoUrl) ?>" aria-label="Investor Daily Indonesia" <?= str_starts_with($footerLogoUrl, 'http') ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
              <img src="<?= esc($footerLogo) ?>" alt="Investor Daily Indonesia" class="footer-logo-id">
            </a>
          <?php endif; ?>
        </div>

        <?php /* Ikon Media Sosial di bawah Logo */ ?>
        <div class="footer-socials">
          <?php
            $igUrl = $site['socials']['instagram'] ?? 'https://www.instagram.com/investordailyid/';
            $ytUrl = $site['socials']['youtube'] ?? 'https://www.youtube.com/@InvestorDaily';
            $fbUrl = $site['socials']['facebook'] ?? 'https://www.facebook.com/investordailyid/';
            $tiktokUrl = $site['socials']['tiktok'] ?? ($site['socials']['tiktok_url'] ?? 'https://www.tiktok.com/@investordaily');
          ?>
          <?php /* Instagram */ ?>
          <?php if (!empty($igUrl)): ?>
            <a href="<?= esc($igUrl) ?>" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="Instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </a>
          <?php endif; ?>

          <?php /* YouTube */ ?>
          <?php if (!empty($ytUrl)): ?>
            <a href="<?= esc($ytUrl) ?>" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="YouTube">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
            </a>
          <?php endif; ?>

          <?php /* Facebook */ ?>
          <?php if (!empty($fbUrl)): ?>
            <a href="<?= esc($fbUrl) ?>" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="Facebook">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
          <?php endif; ?>

          <?php /* TikTok */ ?>
          <?php if (!empty($tiktokUrl)): ?>
            <a href="<?= esc($tiktokUrl) ?>" target="_blank" rel="noopener noreferrer" class="social-circle-btn" aria-label="TikTok">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.29 0 .58.04.86.11V9.42a6.37 6.37 0 0 0-.86-.06A6.34 6.34 0 0 0 3.1 15.7a6.34 6.34 0 0 0 10.82 4.48 6.27 6.27 0 0 0 1.86-4.51v-6.6a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-.96-.5z"/>
              </svg>
            </a>
          <?php endif; ?>
        </div>
      </div>

      <?php /* Bagian Tengah: Teks Copyright */ ?>
      <div class="footer-copy">
        &copy; Copyright <?= date('Y') ?>. <?= esc($site['copyright'] ?? 'Investor Daily Summit.') ?>
      </div>

      <?php /* Sisi Kanan: Contact Us & Phone */ ?>
      <?php
        $cPhone = $site['contact_phone'] ?? '+62 812-1267-8789';
        $cLabel = $site['contact_label'] ?? 'Phone';
        $cUrl   = $site['contact_url'] ?? '';
        $cleanPhone = preg_replace('/[^0-9+]/', '', $cPhone);
        $finalContactLink = !empty($cUrl) ? $cUrl : ('tel:' . $cleanPhone);
        $isExternal = str_starts_with($finalContactLink, 'http://') || str_starts_with($finalContactLink, 'https://');
      ?>
      <div class="footer-contact">
        <div class="footer-contact-title">Contact Us:</div>
        <div class="footer-phone-static" aria-label="Hubungi kami di <?= esc($cPhone) ?>">
          <div class="footer-phone-details">
            <span class="footer-phone-label"><?= esc($cLabel) ?></span>
            <span class="footer-phone-number"><?= esc($cPhone) ?></span>
          </div>
        </div>
      </div>

    </div>
  </div>
</footer>
