<!-- Modal -->
<div class="modal fade" id="downloadCatalogModal" style="background-color: #AFAAA5; " tabindex="-1"
    aria-labelledby="downloadCatalogModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-0 p-5" style="background-color: #F2EFEB; border-radius: 34px !important;">
            <div class="modal-header border-bottom-0">
                <p style="font-size: 30px; letter-spacing: 0.9px; line-height:43px; font-weight: 500;">Download Catalog
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ @$catalogDownloadForm }}" id="downloadCatalogModalForm" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-5">
                        <label for="exampleInputPassword1" class="form-label visually-hidden">Full Name</label>
                        <input type="text" class="form-control form-control-lg custom-input" placeholder="Full Name*"
                            id="exampleInputPassword1" name="name" required>
                    </div>
                    <div class="mb-5">
                        <label for="exampleInputEmail1" class="form-label visually-hidden">Email</label>
                        <input type="email" class="form-control form-control-lg custom-input" placeholder="Email*"
                            id="exampleInputEmail1" name="email" required>
                    </div>

                    <div class="mb-5">
                        <label for="exampleInputEmail2" class="form-label visually-hidden">Phone</label>
                        <input type="tel" class="form-control form-control-lg custom-input" placeholder="Phone"
                            id="exampleInputEmail2" name="mobile">
                    </div>
                    {{-- <div class="my-5">&nbsp;</div> --}}
                    <div class="g-recaptcha" id="productCatalogWidget">
                    </div>
                    <small class="d-none mb-3 captcha_error">Please verify captcha</small>
                    <button type="submit" class="d-none" id="catalogFormSubmit">submit</button>
                    <div class="mb-5">
                        <button class="fs-5 section-link text-uppercase submit-catalog-text float-end" onclick="event.preventDefault();googleCaptchaForms.submit('downloadCatalogModalForm')"
                            type="button">Submit Form <img src="/img/icons/right-arrow.svg" alt="" width=10
                                class=" float-end ms-2"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
