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
            <div class="spa-page animation">
                <img src="https://t4.ftcdn.net/jpg/02/13/31/31/500_F_213313194_wUYEzeUI4jnHZZRgE8baDbQX49Zerfge.jpg" width="44" />
            </div>
            <div class="spa-page current" data-page='1'>
                <div class="card">
                    <div class="card-header">
                        <h4>Business Information</h4>
                    </div>
                    <div class="card-body">
                        <form id="maps_verification_form">
                            <input type="hidden" name="action" value="maps_info" />
                            <div class="form-group">
                                <label>Name of Business</label>
                                <span class="warning"><i class="fa fa-warning"></i>* Required field</span>
                                <input type="text" name="maps_company_name" required="required" placeholder="Name of Business"/>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <span class="warning"><i class="fa fa-warning"></i>* Required field</span>
                                <input type="text" name="maps_firstname" required="required" placeholder="First Name"/>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <span class="warning"><i class="fa fa-warning"></i>* Required field</span>
                                <input type="text" name="maps_lastname" required="required" placeholder="Last Name"/>
                            </div>
                            <div class="form-group">
                                <label>Telephone Number</label>
                                <span class="warning"><i class="fa fa-warning"></i>* Required field</span>
                                <input type="text" name="maps_phone_number" required="required" placeholder="Telephone Number"/>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="prev" @click="previousPage($event)"><i class="fa fa-arrow-left"></i>&nbsp;Back</button>
                        <button class="next" @click="onInfo($event)">Continue <i class="fa fa-next"></i></button>
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
                                <p class="maps_document_formats">Please upload a file in one of these formats:</p>
                                <p class="maps_document_formats">.doc, .docx, .pdf, .jpg, .jpeg, .png</p>
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
                        <button id="upload_button" class='disabled' @click="onUploadDocument($event, this)">Upload Document <i class="fa fa-next"></i></button>
                        <button id="pay_button" class='hide' @click="nextPage">Payment <i class="fa fa-next"></i></button>
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
                            <input type="hidden" name="action" value="maps_payment" />
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
            <div id="page-thanks" class="spa-page">
                <div class="card">
                    <div class="card-header">
                        <h4>Your application is submitted</h4>
                    </div>
                    <div class="card-body">
                        <p class="maps_message maps_message_thanks">Thank You!</p>
                        <p class="maps_document_instructions">We have reveiced your application.</p>
                    </div>
                    <div class="card-footer">
                        <button @click="closeWizzard()">Back to Profile Settings</button>
                    </div>
                </div>
                
            </div>
            <div id="page-sorry" class="spa-page">
                <div class="card">
                    <div class="card-header">
                        <h4>An error occured</h4>
                    </div>
                    <div class="card-body">
                        <p class="maps_message maps_message_thanks">We are sorry</p>
                        <p class="maps_document_instructions">An error occured while submitting your application.</p>
                        <p class="maps_document_instructions">If the problem persists, please <a href="/contact" target="_blank">contact us</a>.</p>
                    </div>
                    <div class="card-footer">
                        <button @click="closeWizzard()">Close</button>
                    </div>
                </div>
                
            </div>
        </div>
    </script>
<?php endif; ?>