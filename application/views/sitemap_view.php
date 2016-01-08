<?php
/*
 *  FINDMYFARE.COM - Team Innovation 
 *  ---------------------------------
 */
?>

</head>
<body>
    <div class="body_wrapper fixed_nav_pad">
        <?php $this->load->view('template/nav-menu'); 
        
        $title = '';
        $cont = '';
        $xmlpath = 'xml';
        if(isset($_GET["chk"]) && $_GET["chk"]=='preview'){
            $xmlpath = 'xml-preview';
        }
        
        if (file_exists("cms/$xmlpath/static_xml_1.xml")) {
            $xml = simplexml_load_file("cms/$xmlpath/static_xml_1.xml");
            foreach ($xml->children() as $books) {
                    //'static_page_id' => trim($main->static_page_id['static_page_id']),
                    $title = (string)($books->static_page_title['static_page_title']);
                    $cont = (string)($books->static_page_content['static_page_content']);
            }
        }
        
        ?>
</div>
<div class="content set_margin_top_82">
    <div class="container fmf-content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="deal_widget">
                    <div class="headers">
                        <h1><?php echo $title ?></h1>
                        <span> Innovative travel solutions to discover the world. </span>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo htmlspecialchars_decode(stripslashes($cont)) ?>
                            </div>                                
                            <div class="col-md-12">
                                <div class="tab">
                                    <div id="tab-main-1" class="tab-main">About Us</div>
<!--                                    <div id="tab-main-2" class="tab-main">Our Partners</div>-->
                                    <div id="tab-main-3" class="tab-main">Certified By</div>
                                    <div class="tab-content" id="tab-content-1">
                                        <br/>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/about-us-point.jpg">
                                                <br>
                                                <h6><b>We are crazy about Traveling</b></h6>
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">We Live and Breathe Travel!</h1>
                                                <p>
                                                    In findmyfare.com we love travelling, holidaying and the excitement that 
                                                    comes with it. We live and breathe travel every second. Findmyfare.com's services 
                                                    are a product of commitment to spread our excitement about travel to everybody.
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" class="img" src="https://fmffiles.s3.amazonaws.com/images/about-us-point.jpg">
                                                <br>
                                                <h6><b>Enjoy hassle free journey every time</b></h6>
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">We pledge a Conscientious Service!</h1>
                                                <p>
                                                    All our services are designed after being in the shoes of customers.
                                                    We understand the difficulties, requirements and excitements of travelers.
                                                    That is why our service does not end with ticket booking. We go lengths to
                                                    make your journey free of hassle of any kind from the moment you book the 
                                                    ticket with us to the end of the trip.  
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/about-us-point.jpg">
                                                <br>
                                                <h6><b>We are the pioneering innovators in travel solutions </b></h6>
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">We Innovate the way Forward!</h1>
                                                <p>
                                                    We know the travel requirements are evolving with the demand. Only way to
                                                    satisfy them is be a pioneering innovator. We are one. We are the largest
                                                    online travel service provider and our products and services are the most
                                                    innovative in the industry. We forever will march on this path to satisfy
                                                    every single one of our customers.   
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/about-us-point.jpg">
                                                <br>
                                                <h6><b>A team of travel loving members dedicating their time to excite our customers travels </b></h6>
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">We are a Flat structured Team!</h1>
                                                <p >
                                                    We here in findmyfare.com are a member of a highly coordinating, flat
                                                    structured team with the sole goal of make our customers journey comfortable
                                                    and exciting. Our members are so driven that they will become invisible 
                                                    partner of your journey once you book a ticket.    
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/about-us-point.jpg">
                                                <br>
                                                <h6><b>Our Expertise and professionalism satisfy customers every single time. </b></h6>
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">We brim with Expertise and Professionalism!</h1>
                                                <p>
                                                    We are a lucky group of members who converted what excites us into our 
                                                    expertise. We are able to provide a total travel solutions to all our 
                                                    customer due to our expertise in the field. We our expertise will be at 
                                                    your service every moment of your travel. We pledge for it. Coupling this 
                                                    with outstanding conduct of professionalism, we satisfy you every single time!    
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-content-2" hidden class="tab-content">
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage" style=" margin-top: 2%;">
                                                <img src="https://fmffiles.s3.amazonaws.com/images/partners/sam.jpg">                                    
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">Sampath Bank</h1>
                                                <p>
                                                    The fastest growing bank of Sri Lanka is in line with our goal of 
                                                    providing customers superior services.
                                                    <br><br>
                                                    <b>Findmyfare.com</b>'s partnership with Sampath Bank will help travelers' by 
                                                    providing affordable travel plans and financial flexibility. The 
                                                    partnership has already provided travel-finance solutions such as 
                                                    easy payment options and ticket rate slashes. This partnership also 
                                                    provides tight insulation over the payment method using enStage. 
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img src="https://fmffiles.s3.amazonaws.com/images/partners/com.jpg">                                    
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">Commercial Bank</h1>
                                                <p>
                                                    With a highly proven and decorated track record Commercial Bank is one 
                                                    of the most reliable banks in the country. 
                                                    <br><br>
                                                    The partnership with the bank ensures <b>Findmyfare.com</b> to provide its 
                                                    consumers with secure, reliable and instant payment mechanisms. 
                                                    The payment gateway with the bank is insulated with additional security 
                                                    options such as 3D Secure. 
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img src="https://fmffiles.s3.amazonaws.com/images/partners/3d.jpg">                                    
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">3D Secure</h1>
                                                <p>
                                                    3D Secure is a protocol that assures the online payment protection. By adopting 
                                                    this standard, any possibility of misconduct or unauthorized transactions can be eliminated.
                                                    <br><br>
                                                    <b>Why do we affiliate with 3D secure?</b>
                                                    <br>
                                                    To ensure the highest degree of fund transaction safety and weed out any possibility 
                                                    of misconduct or fraudulence.   
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img src="https://fmffiles.s3.amazonaws.com/images/partners/trav.jpg">                                    
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">Travelport</h1>
                                                <p>
                                                    Travelport is an e-commerce service provider whose Global Distribution
                                                    System allows the travel industry to aggregate data, search information 
                                                    and conduct transaction processing service. The clientele of the 
                                                    system include all major airline services and travel service providers. 
                                                    <br><br>
                                                    Findmyfare.com adopted this system in order to equip its consumers with
                                                    the ability to search and choose the air carrier they prefer. The partnership
                                                    guarantees the consumer of the ticket they are buying and makes the booking
                                                    instantaneous in your preferred airline. 
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab-content-3" hidden class="tab-content">
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage"  >
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/iata-logo.gif">
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">International Air Transport Association</h1>
                                                <p>IATA is the leader and regulator of world airline industry. Apart
                                                    from keeping the industry fare and competitive, the entity credits 
                                                    travel agents with professional credentials. This also ensures
                                                    protection of travelers. Any ticket purchase through IATA accredited 
                                                    agents are vouched by IATA itself.  
                                                    <br><br>
                                                    IATA Accreditation validates our reliability, professionalism and
                                                    quality. It also ensures the authenticity of every purchase and 
                                                    prioritizes consumer protection. 
                                                    <br><br>
                                                    <b>Findmyfare.com</b> is a fully accredited by IATA under the agent number <b>07302503</b>.
                                                </p>
                                            </div>
                                        </div>                            
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/SlCivil.gif">
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">Civil Aviation</h1>
                                                <p>To facilitate through strategic planning and effective  regulation, 
                                                    the operation of a safe, secure and  efficient  national civil aviation industry 
                                                    that conforms to  International Standards and Recommended Practices. 
                                                    <br><br>
                                                    <b>Findmyfare.com</b> is a fully certified by Sri Lanka Civil Aviation under the agent number <b>A877</b>.
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/SLTouism.gif">
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">Sri Lanka Tourism Board</h1>
                                                <p>Sri Lanka tourism awards accreditation to travel agents who meet their criterion.
                                                    It also symbolizes the support of the local industry. 
                                                    <br><br>
                                                    Why do we affiliate with Sri Lanka Tourism? This affiliation is
                                                    to validate our presence in local travel industry. This ensures 
                                                    our financial stability, quality of the service and validates the 
                                                    offers we make to our consumers. 
                                                    <br><br>
                                                    <b>Findmyfare.com</b> is a fully certified by Sri Lanka Tourism Board under the agent number <b>TS TA 13337</b>.
                                                </p>
                                            </div>
                                        </div>
                                        <!------------Rows-------------->
                                        <div class="aboutUsContainer">
                                            <div class="aboutUsImage backWhite ">
                                                <img class="img" src="https://fmffiles.s3.amazonaws.com/images/geo-trust.gif">
                                            </div>
                                            <div class="aboutUsContent">
                                                <h1 class="questTxt">GEO Trust</h1>
                                                <p>GeoTrust is one of the leading digital certificate provider, 
                                                    assuring the security of any e-commerce business. These 
                                                    certificates ensures the identity of business entities and/or 
                                                    individuals in online transactions.
                                                    <br><br>
                                                    <b>Findmyfare.com</b> adopted the GeoTrust to ensure the security of personal
                                                    details (names, telephone numbers, email addresses) of its customers. 
                                                    The security system allows the data to be encrypted with highest quality
                                                    encryption algorithms.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

    <script>
    $(document).ready(function() {
        $('#tab-main-1').css('color', '#FFF');
        $('#tab-main-1').css('background-color', '#265C8C');

        $('#tab-main-1').click(function() {
            $('.tab-main').css('color', '#084B8A');
            $('.tab-main').css('background-color', 'transparent');
            $('.tab-main').css('border-bottom-color', '#BDBDBD');
            $(this).css('color', '#FFF');
            $(this).css('background-color', '#265C8C');
            $(this).css('border-bottom-color', 'transparent');
            $('.tab-content').fadeOut();
            $('#tab-content-1').fadeIn();
        });
        $('#tab-main-2').click(function() {
            $('.tab-main').css('color', '#084B8A');
            $('.tab-main').css('background-color', 'transparent');
            $('.tab-main').css('border-bottom-color', '#BDBDBD');
            $(this).css('color', '#FFF');
            $(this).css('background-color', '#265C8C');
            $(this).css('border-bottom-color', 'transparent');
            $('.tab-content').fadeOut();
            $('#tab-content-2').fadeIn();
        });
        $('#tab-main-3').click(function() {
            $('.tab-main').css('color', '#084B8A');
            $('.tab-main').css('background-color', 'transparent');
            $('.tab-main').css('border-bottom-color', '#BDBDBD');
            $(this).css('color', '#FFF');
            $(this).css('background-color', '#265C8C');
            $(this).css('border-bottom-color', 'transparent');
            $('.tab-content').fadeOut();
            $('#tab-content-3').fadeIn();
        });
    });
</script>
<style>
    .tab-main {  float: left;
                 border: 1px solid #bdbdbd;
                 padding: 10px;
                 cursor: pointer;
                 font-weight: bold;
    }
    .aboutUsImage {  float: left;
                     width: 150px;
                     text-align: center;
                     color: #0174DF;
                     min-width: 110px;}
    .aboutUsContainer {float: left;
                       opacity: 0.8;
                       border-bottom: 5px solid rgba(29, 26, 26, 0.09);
                       padding: 10px;
                       margin-top: 15px;
                       -webkit-transition: opacity 0.5s;
                       -moz-transition: opacity 0.5s;
                       -ms-transition: opacity 0.5s;
                       -o-transition: opacity 0.5s;
                       transition: opacity 0.5s;}

    .aboutUsContent {padding: 10px;
                     margin-left: 2px;
                     border-radius: 10px;
                     color: #000000;
                     height: auto;
                     float: left;
                     width: 78%;
                     text-align: justify;
                     -webkit-transition: background-color 0.5s;
                     -moz-transition: background-color 0.5s;
                     -ms-transition: background-color 0.5s;
                     -o-transition: background-color 0.5s;
                     transition: background-color 0.5s;}
    </style>
