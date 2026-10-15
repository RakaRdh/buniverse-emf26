<!-- Speaker Detail Modal Popup (Figma Spec) -->
<div class="speaker-modal-backdrop" id="speakerModal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="speaker-modal-dialog">
    
    <!-- Decorative Corner Ribbons (Gambar 5: kiri atas & kanan bawah, 1/4 visible) -->
    <img src="<?= base_url('assets/img/ribbon.png') ?>" alt="" class="modal-ribbon modal-ribbon-tl" aria-hidden="true">
    <img src="<?= base_url('assets/img/ribbon.png') ?>" alt="" class="modal-ribbon modal-ribbon-br" aria-hidden="true">

    <!-- Close Button -->
    <button class="speaker-modal-close" id="speakerModalClose" aria-label="Tutup popup">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>

    <!-- Modal Content -->
    <div class="speaker-modal-body">
      <!-- Speaker Photo Left -->
      <div class="speaker-modal-photo-col">
        <div class="speaker-modal-photo-wrap">
          <img src="" alt="" id="modalSpeakerPhoto" class="speaker-modal-img">
        </div>
      </div>

      <!-- Speaker Text Info Right -->
      <div class="speaker-modal-info-col">
        <h3 class="speaker-modal-name" id="modalSpeakerName"></h3>
        <p class="speaker-modal-role" id="modalSpeakerRole"></p>
        <p class="speaker-modal-bio" id="modalSpeakerBio" style="display: none;"></p>
      </div>
    </div>

  </div>
</div>

