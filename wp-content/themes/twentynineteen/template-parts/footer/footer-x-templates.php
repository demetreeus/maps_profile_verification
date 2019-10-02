<?php

/**
 * Renders the x-templates used by the vue app
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

if (is_active_sidebar('sidebar-1')) : ?>
    <script type="text/x-template" id="maps_verification_wizzard">
        <div id="wizzard" class="spa">
            <div class="spa-page" data-page='1'>
                <div class="card">
                    <div class="card-header">
                        Business Information
                    </div>
                    <div class="card-body">
                        <form id="maps_verification_form">
                            <div class="form-group">
                                <label>Name of Business</label>
                                <input type="maps_company_name" />
                            </div>
                            <div class="form-group">
                                <label>Name of Applicant</label>
                                <input type="maps_company_name" />
                            </div>
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="maps_applicant_name" />
                            </div>
                            <div class="form-group">
                                <label>Telephone Number</label>
                                <input type="maps_phone_number" />
                            </div>
                            <div class="form-group">
                                <label>Document</label>
                                <input type="file" />
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button @click="nextPage(this)">Upload Document <i class="fa fa-next"></i></button>
                    </div>
                </div>
            </div>
            <div class="" data-page='2'>
                <div class="card">
                    <div class="card-header">
                        <h4>Send Documentation</h4>
                    </div>
                    <div class="card-body">
                        <p class="maps_document_instructions">Please upload a picture or an official document showing your business or organization's name and address</p>
                        <p class="maps_document_instructions">You can use a:</p>
                        <ul class="maps_document_list">
                            <li>Business utility or phone bill</li>
                            <li>Business licence</li>
                            <li>Business tax file</li>
                            <li>Certificate of formation</li>
                            <li>Articles of incorporation</li>
                        </ul>
                        <div class="maps_document_uploader">
                            <h3>Business Documentation</h3>
                            <div class="maps_document_formats_wrap">
                                <p class="maps_document_formats">
                                    Please upload a file in one of these formats:
                                </p>
                                <p class="maps_document_formats">
                                    .doc, .docx, .pdf, .jpg, .jpeg, .png
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button @click="nextPage">Proceed and Pay <i class="fa fa-next"></i></button>
                    </div>
                </div>
                
            </div>
            <div class="spa-page" data-page='3'>
                <div class="card">
                    <div class="card-header">
                        <h3>Payment</h3>
                    </div>
                    <form action="/charge" method="post" id="payment-form">
                        <div class="card-body">
                            <div class="form-row">
                                <label for="card-element">
                                Credit or debit card
                                </label>
                                <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                                </div>

                                <!-- Used to display Element errors. -->
                                <div id="card-errors" role="alert"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button>Submit Payment</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </script>
<?php endif; ?>