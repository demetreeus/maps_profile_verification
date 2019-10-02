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
            <div class="spa-page current" data-page='1'>
                <div class="card">
                    <div class="card-header">
                        <h4>Business Information</h4>
                    </div>
                    <div class="card-body">
                        <form id="maps_verification_form">
                            <div class="form-group">
                                <label>Name of Business</label>
                                <input type="maps_company_name" placeholder="Name of Business"/>
                            </div>
                            <div class="form-group">
                                <label>Name of Applicant</label>
                                <input type="maps_company_name" placeholder="Name of Applicant"/>
                            </div>
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="maps_applicant_name" placeholder="Company Name"/>
                            </div>
                            <div class="form-group">
                                <label>Telephone Number</label>
                                <input type="maps_phone_number" placeholder="Telephone Number"/>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="prev" @click="previousPage($event)"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                        <button class="next" @click="nextPage($event)">Continue <i class="fa fa-next"></i></button>
                    </div>
                </div>
            </div>
            <div class="spa-page" data-page='2'>
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
                            <h5>Business Documentation</h5>
                            <div class="maps_document_formats_wrap">
                                <p class="maps_document_formats">
                                    Please upload a file in one of these formats:
                                </p>
                                <p class="maps_document_formats">
                                    .doc, .docx, .pdf, .jpg, .jpeg, .png
                                </p>
                            </div>
                            <div id="document_placeholder" class="hide">
                                <img src='' />
                            </div>
                            <div class="form-group">
                                <label>Document</label>
                                <input id="document_input" type="file" accept=".doc, .docx, .pdf, image/*" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="prev" @click="previousPage($event)"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                        <button id="upload_button" class='disabled' @click="onUploadDocument($event, this)">Upload <i class="fa fa-next"></i></button>
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
                            <button class="prev" @click="previousPage($event)"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                            <button>Submit Payment</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </script>
<?php endif; ?>