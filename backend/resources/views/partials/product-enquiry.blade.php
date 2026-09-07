<!-- Modal -->
<div class="modal fade" id="productEnquiryModal" style="background-color: #AFAAA5; " tabindex="-1" aria-labelledby="productEnquiryModal" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-0 p-5" style="background-color: #F2EFEB; border-radius: 34px !important;">
      <div class="modal-header border-bottom-0">
        <p style="font-size: 30px; letter-spacing: 0.9px; line-height:43px; font-weight: 500;">Product Enquiry</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('mail.product.send') }}" id="product_inquiry" class="row" method="post" enctype="multipart/form-data">
          @csrf
          <div class="col-md-6 mb-5">
            <label for="enquiryFullName" class="form-label visually-hidden">Full Name</label>
            <input type="text" class="form-control form-control-lg custom-input" placeholder="Full Name *" id="enquiryFullName" name="full_name" required>
          </div>
          <div class="col-md-6 mb-5">
            <label for="enquiryPosition" class="form-label visually-hidden">Position</label>
            <input type="text" class="form-control form-control-lg custom-input" placeholder="Position" id="enquiryPosition" name="position">
          </div>

          <div class="col-md-6 mb-5">
            <label for="enquiryCompany" class="form-label visually-hidden">Company</label>
            <input type="text" class="form-control form-control-lg custom-input" placeholder="Company *" id="enquiryCompany" name="company" required>
          </div>
          <div class="col-md-6 mb-5">
            <label for="enquiryEmail" class="form-label visually-hidden">Email</label>
            <input type="email" class="form-control form-control-lg custom-input" placeholder="Email *" id="enquiryEmail" name="email" required>
          </div>
          <div class="col-md-6 mb-5">
            <label for="enquiryPhone" class="form-label visually-hidden">Phone</label>
            <input type="tel" class="form-control form-control-lg custom-input" placeholder="Phone" id="enquiryPhone" name="phone">
          </div>

          <div class="col-md-6 mb-5">
            <label for="enquiryCity" class="form-label visually-hidden">City</label>
            <input type="text" class="form-control form-control-lg custom-input" placeholder="City" id="enquiryCity" name="city">
          </div>
          <div class="col-md-6 mb-5">
            <label for="enquiryCountry" class="form-label visually-hidden">Country</label>
            <input type="text" class="form-control form-control-lg custom-input" placeholder="Country" id="enquiryCountry" name="country">
          </div>
          <div class="col-md-12 mb-5">
            <label for="enquiryMessage" class="form-label visually-hidden">Message</label>
            <textarea id="enquiryMessage" name="i_message" class="form-control form-control-lg custom-input" placeholder="Message"></textarea>
          </div>
          {{-- <div class="my-5">&nbsp;</div> --}}
          <div class="mb-5" id="submit_button">
          <div class="g-recaptcha" id="productInquiriesWidget">
            </div>
            <small class="d-none mb-3 captcha_error">Please verify captcha</small>
            <button type="submit" class="d-none" id="productEnquiryFormSubmit">submit</button>
            <div class="d-none">
              <input type="text" required id="enquiryInvalidForm">
            </div>
            <button class="fs-5 section-link text-uppercase submit-catalog-text float-end" type="button" onclick="googleCaptchaForms.submit('product_inquiry')">Submit Form <img src="img/icons/right-arrow.svg" alt="" width=10 class=" float-end ms-2"></button>
          </div>
          <div class="mb-5 d-none" id="submitted_msg">
            <p class="fs-5 float-end">Please wait while we submit the form</p>
          </div>

          <div id="inquiry_msg" class="d-none">
            Thank you for the product inquiry
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
