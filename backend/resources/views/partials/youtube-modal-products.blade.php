<!-- Modal for Products Page -->
<div class="modal fade" id="youtubeModalProducts" tabindex="-1" aria-labelledby="youtubeModalProductsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-0">
      <div class="modal-body p-0">
        <div class="iframe-container">
            <iframe data-url="" title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var youtubeModal = document.getElementById('youtubeModalProducts');
    var iframe = youtubeModal.querySelector('iframe');

    youtubeModal.addEventListener('show.bs.modal', function (event) {
        var trigger = event.relatedTarget;
        var videoUrl = trigger.getAttribute('data-video-url');
        iframe.src = videoUrl + '?autoplay=1';
    });

    youtubeModal.addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
});
</script>
