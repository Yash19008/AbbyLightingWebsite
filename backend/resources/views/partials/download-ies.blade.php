<!-- Modal -->
<div class="modal fade" id="downloadCatalogModal" style="background-color: #AFAAA5" tabindex="-1"
  aria-labelledby="downloadCatalogModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-0 border-0" style="background-color: #C4C0BB;">
      <div class="modal-header border-bottom-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-5 pt-0">
        <p class="section-text bolder mb-0 pb-0">IES Files</p>
        @foreach($variant->variantFiles as $variantFile)
        @if(@$variantFile->file_type === 'ies')
        <p class="section-text p-0 m-0  border-bottom">{{@$variantFile->file}}
          <a class="section-text" href="{{asset('storage/uploads/product_variant_ies_files/'.@$variantFile->file)}}" target="_blank" download><i
              class="fa fa-download success font-small-2 mr-1 cursor-pointer"></i></a>
        </p>
        @endif
        @endforeach
      </div>
    </div>
  </div>
</div>