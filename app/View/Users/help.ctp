<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>


<?php echo $this->Form->create('help', array('type' => 'file', 'id' => 'help')); ?>

<div class="box box-solid">
            <div class="box-header with-border">
              <center><h3 class="box-title" style="font-weight: bolder"><?php echo __('Help'); ?></h3></center>
            </div>
    
            <div class="box-body">
              <div class="box-group" id="accordion">
                  
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title"><i class="fa fa-fw fa-hand-o-right"></i> 
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                        Citizen Registration
                      </a>
                    </h4>
                      
                  </div>
                  <div id="collapse1" class="panel-collapse collapse in">
                    <div class="box-body hlp_Ftsize">
                        
                        <p><i class="fa fa-fw fa-caret-right"></i> Go to <b>Citizen Registration</b></p>
                        <ul>
                            <li>Red Asterisk (*) are mandatory /compulsory fields.</li>
                            <li>Fields not showing Red Asterisk (*) are optional.</li>
                            <li>Password Policy : Password should contain at least 1 Uppercase, 1 Lowercase, 1 digit, 1 special character)</li>
                        </ul>
                        <p><i class="fa fa-fw fa-sort-numeric-asc"></i> Steps for Citizen Registration</p>
                            <ol>
                                <li>Enter valid 10 digit mobile number</li>
                                <li>Enter username of your preference.</li>
                                <li>Check username is available by click on Check Availability button to make sure username is available. If username is available then only user has allowed to create username</li>
                                <li>Enter password (Password should contain at least 1 Uppercase, 1 Lowercase, 1 digit, 1 special character)</li>
                                <li>Enter retype password (Retype password should be same as entered password)</li>
                                <li>Read the characters from the captcha image</li>
                                <li>And enter text in field</li>
                                <li>Click on submit button for to save records. (If record save successfully then success message is displayed)</li>
                                <li>By click on cancel button user re-direct to home page</li>
                            </ol>
                            <ul>
                                <li>Enter the details in citizen registration form and click on submit button for to generate citizen username &amp; password.</li>
                            </ul>
                    </div>
                  </div>
                </div>
                
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title"><i class="fa fa-fw fa-hand-o-right"></i> 
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                        Property Valuation
                      </a>
                    </h4>
                      
                  </div>
                  <div id="collapse2" class="panel-collapse collapse">
                    <div class="box-body hlp_Ftsize">
                        <p><i class="fa fa-fw fa-asterisk"></i> <strong>Purpose :</strong>
                            To estimate the current value of a property by feeding relevant information  such as city, location, type of house, area etc..
                        </p>
                        <p><i class="fa fa-fw fa-asterisk"></i> <strong>Description :</strong>
                            Property Valuation generally uses a combination of property usage, valuation rule formulated by state government authority, development zones, construction type, depreciation if any, road connectivity etc.   
                            The results of each are weighted, analyzed and then reported as a final estimate of value based on a requested data
                        </p>
                        
                        <p><i class="fa fa-fw fa-angle-double-down"></i> <b>Property valuation depends upon</b></p>
                            <i class="fa fa-fw fa-caret-right"></i> Rate Chart prepared by the Department considering
                            <ul>
                                <li>Location wise major usage&nbsp; factors</li>
                                <li>Government regulations and activities</li>
                                <li>Economic activities and trends</li>
                                <li>Future benefits</li>
                                <li>Age of the property &amp; construction type</li>
                            </ul>
                            <i class="fa fa-fw fa-caret-right"></i> Valuation factors for real property, such as
                            <ul>
                                <li>Area of construction</li>
                                <li>Area of land</li>
                                <li>Parking area</li>
                                <li>Number of cashew-nut trees</li>
                                <li>Area of non-cultivated land</li>
                            </ul>
                        <p><i class="fa fa-fw fa-sort-numeric-asc"></i> <b>What is required</b></p>
                            <ol>
                                <li>Valid Citizen user credential</li>
                                <li>Property location details</li>
                                <li>Valuation Zone Details if any</li>
                                <li>Property Usage</li>
                            </ol>
                        <p><i class="fa fa-fw fa-sort-numeric-asc"></i> <b>Prerequisite</b></p>
                            <ol>
                                <li>Open NGDRS site</li>
                                <li>Register to a site as a citizen to get access for NGDRS site</li>
                                <li>Login to the NGDRS by citizen credential</li>
                                
                            </ol>
                        
                        <p><i class="fa fa-fw fa-check-square"></i> <b>Steps for to estimate property valuation</b></p>
                        <p><i class="fa fa-fw fa-sort-numeric-asc"></i> <b>a) Property Location</b></p>
                            <ol>
                                <li>Select Financial year. Default is Current Financial Year. Citizen can select previous financial year so that valuation for particular year is also possible.</li>
                                <li>Select District</li>
                                <li>Taluka and Corporation/Municipal councils will be available for selected districts.</li>
                                <li>Select Taluka or Corporation/Municipal councils.</li>
                                <li>Select City/ Village from the list.</li>
                                <li>Select location</li>
                                <li>Select location within City/ Village</li>
                                <li>View Survey Number: will show the survey numbers for particular location.</li>
                            </ol>
                            <ul>
                                <li>View survey number for the confirmation or reference</li>
                            </ul>
                        <p><i class="fa fa-fw fa-sort-numeric-asc"></i> <b>b) Property Usage</b></p>
                        <p>For which property usage user want to find valuation select that correct property usage by following steps</p>
                        <p><strong>Selection Process A</strong></p>
                        <ol>
                            <li>Go to Main Usage and click on + icon for to open usages.</li>
                            <li>Select particular usage then details will be available in left hand panel.</li>
                            <li>Select Detail usage</li>
                        </ol>
                        <p><strong>Selection Process B</strong></p>
                        <ol>
                            <li>Enter/type usage in search box. List of Detail usages will be displayed in left hand panl</li>
                            <li>Select particular usage then details will be available in left hand panel.</li>
                            <li>Select Detail usage.</li>
                        </ol>
                        <ol start="4">
                            <li>Select the property usage</li>
                        </ol>
                        <p>By selecting property usage its dependencies are appear</p>
                        <ol>
                            <li>Select the construction type</li>
                            <li>Select the age 0 to 2 Years</li>
                            <li>Select Road Vicinity</li>
                            <li>Add area in constructed property</li>
                            <li>Select Shop Floor</li>
                            <li>Add Mezzanine Floor Area</li>
                        </ol>
                         <p><i class="fa fa-fw fa-sort-numeric-asc"></i> <b>c) Calculate & Save</b></p>
                         <ol>
                            <li>Estimate the Property Valuation by simply click on Calculate &amp; Save Button. View valuation Report appear on the screen</li>
                            <li>After estimate property valuation user can also view valuation report by click on view button</li>
                            <li>User can cancel property valuation by click on cancel button</li>
                            <li>By click on exit button user go to welcome page</li>
                        </ol>
                    </div>
                  </div>
                </div>
                  
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title"><i class="fa fa-fw fa-hand-o-right"></i> 
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                        Valuation Rule 
                      </a>
                    </h4>
                      
                  </div>
                  <div id="collapse1" class="panel-collapse collapse">
                    <div class="box-body hlp_Ftsize">
                        <p><i class="fa fa-fw fa-caret-right"></i> Go to <b>Valuation Rule</b></p>
                            <ol>
                                <li>List of valuation rules are listed in table</li>
                                <li>Edit the valuation rule</li>
                                <li>Delete the valuation rule</li>
                                <li>User can copy all sub rule by simply click on copy icon</li>
                                <li>And paste all sub rule to other valuation rule by simply click on paste icon</li>
                                <li>Click on New Rule button for to create new rule</li>
                            </ol>
                        <p><i class="fa fa-fw fa-caret-right"></i> Go to <b>New Rule</b></p>
                            <ul>
                                <li>Select Financial Year. Default is Current Financial Year. User can select previous financial year so that valuation rule for particular year is also possible.</li>
                                <li>Valuation rule is effected on selected date</li>
                                <li>Enter Reference Number. (Refer from valuation rule book)</li>
                                <li>Select Usage Main Category</li>
                                <li>Select Usage Sub Category</li>
                            </ul>
                            <ul>
                                <li>Enter Rule Description. Valuation Rule is applied for this Rule Description and it&rsquo;s consider as a usage sub to sub category. It also consider as a valuation rule name</li>
                                <li>Enter Rule Description in local language</li>
                                <li>Enter Dolr Usage Code</li>
                                <li>Select Yes or No. ( If set this to Yes then it display in property valuation )</li>
                            </ul>
                            <ul>
                                <li>If additional rate is required then click on Yes otherwise click on No. If Click on Yes then Usage Main Category and Sub Category is open</li>
                                <li>Select Usage Main Category</li>
                                <li>Select Usage Sub Category</li>
                            </ul>
                            <ul>
                                <li>If rate comparison is required then click on Yes otherwise click on No. If Click on Yes then Usage Main Category and Sub Category is open. (If set Yes then also set Yes for max value check in sub rule tab and filled the formula for max value.)</li>
                                <li>Select Usage Main Category</li>
                                <li>Select Usage Sub Category</li>
                            </ul>
                            <ul>
                                <li>Check TDR is applicable and click on Yes or No</li>
                                <li>Check where rule is applicable for Urban, Rural or Influence and click on Yes or No</li>
                                <li>Click on Save button for to save Valuation Rule form</li>
                            </ul>
                            <ul><li>After Save Valuation Rule form Rule Item Linkage form is open</li></ul>
                            
                            <p><i class="fa fa-fw fa-caret-right"></i> Go to <b>Rule Items Linkage</b></p>
                            <ol>
                                <li>Select output items.</li>
                                <li>Select input items</li>
                                <li>Click on save button to save form</li>
                            </ol>
                            <ul>
                                <li>Number of sub rules is created by selecting output &amp; input items.</li>
                            </ul>
                            <ul>
                                <li><strong>Formula for number of sub rule creation </strong></li>
                                <li>If select 4 input item and two of them are list item of which having (3,2) list values respectively. And select output item 2</li>
                            </ul>
                                <p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total sub rule </strong>= Input(2+(3*2))*Output(2) = 8*2 = 16</p>
                                    
                            <p><i class="fa fa-fw fa-caret-right"></i> Go to <b>Sub Rule</b></p>
                            <ul>
                                <li>Select output items</li>
                                <li>Enter display order.</li>
                            </ul>
                            <ul>
                                <li>Output items display according to order in valuation report</li>
                                <li>Suppose covered parking has display order is 2 then it listed in second row in valuation report</li>
                            </ul>
                            <ul>
                                <li><strong>Max value Check</strong> :</li>
                            </ul>
                            <ul>
                                <li>Check max value is set to Yes</li>
                                <li>Select Parameter (First Set cursor on max value formula input then select parameter)</li>
                                <li>Select Operator (First Set cursor on max value formula input then select operator)</li>
                                <li>Created formula by selecting parameter and operator</li>
                            </ul>
                            <ul>
                                <li>If rate comparison is set to Yes from valuation rule tab then this max value check is set Yes as a by-default otherwise No is selected as a by-default.</li>
                                <li>If max value check is set to Yes then filled the formula for max value by selecting parameter and operator</li>
                            </ul>
                            <ul>
                                <li><strong>Check Rate Revision</strong> Yes or No. (This now used in Mumbai location. &nbsp; Depreciation rate is applied on constructed rate &ndash; open land)</li>
                                <li>If click Yes Rate Revision then Rate Revision Formula panel is open. Create formula in rate revision by selecting item and operator</li>
                                <li>Select Item</li>
                                <li>Select Operator</li>
                                <li>By selecting Item &amp; operator condition is created</li>
                                <li>By selecting Item &amp; operator Formula is created</li>
                                <li>Click on save button to save rule</li>
                            </ul>
                                <p><strong>Steps to create condition &amp; formula for valuation rule</strong></p>
                            <ul>
                                <li>Set mouse cursor on condition textbox</li>
                                <li>Select Item. (By selecting item then Item Description code is displayed in condition or textbox )</li>
                                <li>Select operator from dropdown (By selecting operator then operator +,- etc.. displayed in condition or formula textbox)</li>
                                <li>If no input is selected and select only condition or operator then formula is created in formula one</li>
                            </ul>
                    </div>
                  </div>
                </div>  
                  
                  <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title"><i class="fa fa-fw fa-hand-o-right"></i> 
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                        Burpsuite
                      </a>
                    </h4>
                      
                  </div>
                  <div id="collapse3" class="panel-collapse collapse">
                    <div class="box-body hlp_Ftsize">
                        <p><i class="fa fa-fw fa-asterisk"></i> <strong>Burpsuite Web Security Testing Tool :</strong>
                            Burp is designed to be used alongside your browser. Burp functions as an HTTP proxy server, and all HTTP/S traffic from your browser passes through Burp.
                            To do any kind of testing with Burp, you need to configure your browser to work with it.
                        </p>
                        <p><i class="fa fa-fw fa-caret-right"></i> <b>Confirm your proxy listener is active</b></p>
                        <ul>
                            <li>First, you need to confirm that Burp's proxy listener is active and working</li>
                            <li>Go to the "Proxy" tab, then the "Options" sub-tab, and look in the "Proxy Listeners" section</li>
                            <li>You should see an entry in the table with the checkbox ticked in the Running column, and "127.0.0.1:8080" showing in the Interface column. 
                                If so, please go to the section "Configure your browser to use the proxy listener</li>
                            <li>If the listener is still not running, then Burp was not able to open the default proxy listener port (8080)</li>
                            <li>You will need to select the table entry, click "Edit", and change the port number of the listener to a different number</li>
                        </ul>
                        <p><i class="fa fa-fw fa-caret-right"></i> <b>Configure your browser to use the proxy listener</b></p>
                        <p>Secondly, you need to configure your browser to use the Burp Proxy listener as its HTTP proxy server. 
                            To do this, you need to change your browser's proxy settings to use the proxy host address (by default, 127.0.0.1) and port (by default, 8080) for both HTTP and HTTPS protocols, with no exceptions. 
                            The details of how to do this vary by browser and version, please use the links below to find out how to configure your browser</p>
                        
                            <ol>
                                <li>If you aren't sure where the built-in proxy settings are, open Chrome and go to the Customize menu</li>
                                <li>In the Customize menu, select Settings, then click on "Show advanced settings"</li>
                                <li>In the "Advanced Settings" section, click the "Change proxy settings ..." button. This will open the relevant configuration options for your host computer</li>
                            </ol>

                    </div>
                  </div>
                </div>
                  
              </div>
            </div>
</div>

<div class="box box-solid">
            <div class="box-header with-border">
              <center><h3 class="box-title" style="font-weight: bolder">User Manual</h3></center>
            </div>       
            <div class="box-body">
              <div class="box-group" id="accordion">
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse11">
                        Property Valuation
                      </a>
                    </h4>
                  </div>
                  <div id="collapse11" class="panel-collapse collapse">
                    <div class="box-body">
                        <div class="PDF">
                            <object data="<?php echo $this->webroot; ?>Documentation/UM-PropertyValuation.pdf" type="application/pdf" width="967" height="800">
                            </object>                      
                        </div>
                    </div>
                  </div>
                </div>
                
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">
                        Document Entry
                      </a>
                    </h4>
                  </div>
                  <div id="collapse12" class="panel-collapse collapse">
                    <div class="box-body">
                      <div class="PDF">
                            <object data="<?php echo $this->webroot; ?>Documentation/UM-DocumentEntry.pdf" type="application/pdf" width="967" height="800">
                            </object>                      
                      </div>
                    </div>
                  </div>
                </div>
                  
                  <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse13">
                        Valuation Rule
                      </a>
                    </h4>
                  </div>
                  <div id="collapse13" class="panel-collapse collapse">
                    <div class="box-body">
                      <div class="PDF">
                            <object data="<?php echo $this->webroot; ?>Documentation/UM-ValuationRule.pdf" type="application/pdf" width="967" height="800">
                            </object>                      
                      </div>
                    </div>
                  </div>
                </div>
                  
              </div>
            </div>
</div>    
          



<?php echo $this->Form->end(); ?>

<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        if (!navigator.onLine)
        {
            // document.body.innerHTML = 'Loading...';
           // window.location = '../cterror.html';
        }
        function disableBack() {
            window.history.forward()
        }

        window.onload = disableBack();
        window.onpageshow = function (evt) {
            if (evt.persisted)
                disableBack()
        }
    });
    var message = "Not Allowed Right Click";
    function rtclickcheck(keyp)
    {
        if (navigator.appName == "Netscape" && keyp.which == 3)
        {
            alert(message);
            return false;
        }
        if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2)
        {
            alert(message);
            return false;
        }
    }
    document.onmousedown = rtclickcheck;
</script>