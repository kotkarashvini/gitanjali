<section id="main">
            <div class="container">
                 <div class="row">
                                 <div class="col-md-12 mb-3 border">
                                     <p> 
                                          <center> 
                                              <h4 class=""><b>Legacy Data Management System(LDMS) for NGDRS</b></h4>
                                       </center> 
                                      
                                      </p>
                  
                                      
                                 </div>
                                 
                             </div>
                    <div class="row">
         
                       <div class="col-md-12" id="two">
                             <center><i class="fa fa-users fa-3x " style="color: #0a3e69;"></i></center>
                             <center><h5><b>Organization</b></h5></center>
                             <div class="row">
                                 <div class="col-md-12 text-center">
<!--                                      <a href="pdf/dummy.pdf"><img src="images/login_button.png" alt="img" class="img-fluid" id="org"></a> --> 
                                     <?php echo "<a class='btn btn-primary butn' href='" . $this->webroot . 'Users' . "/" . 'login' . "'><i class='fa fa-sign-in-alt'></i><span>" . ' Login' . "</span></a>"; ?>
                                 </div>
                                 
                             </div>
                             <div class="row">
                                 <div class="col-md-12">
                                     <p> 
                                          <center> 
                                             
                                       </center> 
                                      
                                      </p>
                  
                                      
                                 </div>
                                 
                             </div>
                       </div>
                    </div>
    
                 </div>
         </section> 
        
                <!--******************************* Main End********************************************************-->
                 
                <!--************************************** Marquee start************************************************-->

		<section>
                <div class="container" id="message">
                    <div class="row">
                            <marquee direction = "left" onMouseOver="this.stop()" onMouseOut="this.start()">
                                  
                                   <span> Our Reach :</span>	
                                   Andaman & Nicobar Island,&nbsp;Dadra & Nagar Haveli,&nbsp;Goa,&nbsp;Himachal Pradesh,&nbsp;iSarita 2.0(NGDRS) : CIDCO Module,&nbsp;Jharkhand,&nbsp;Manipur,&nbsp;Mizoram&nbsp;and Punjab 
                            </marquee>
                    </div>
                </div>
            </section>
              <!-------******************************Marquee End********************************-->	 
    
                <!--*********************************** Slider Start*******************************-->
		
		<section id="banner">
			    <div class="container" style="padding: 0">
				<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
						  <div class="carousel-item active">
<!--							<img src="images/banner1.png" class="d-block w-100" alt="...">--> 
                                                     <?php echo $this->Html->image('./images/banner3.png', array('class' => 'img-fluid', 'width'=>'100%')); ?>
						  </div>
						  <div class="carousel-item">
<!--							<img src="images/banner1.png" class="d-block w-100" alt="...">--> 
                                                       <?php echo $this->Html->image('./images/banner.png', array('class' => 'img-fluid', 'width'=>'100%')); ?>
						  </div>
						  <div class="carousel-item">
<!--							<img src="images/banner1.png" class="d-block w-100" alt="...">--> 
                                                      <?php echo $this->Html->image('./images/banner2.png', array('class' => 'img-fluid', 'width'=>'100%')); ?>
						  </div>
                                                  <div class="carousel-item">
<!--							<img src="images/banner1.png" class="d-block w-100" alt="...">--> 
                                                      <?php echo $this->Html->image('./images/rooledoutstates.png', array('class' => 'img-fluid', 'width'=>'100%')); ?>
						  </div>  
						</div>
						<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
						  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						  <span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
						  <span class="carousel-control-next-icon" aria-hidden="true"></span>
						  <span class="sr-only">Next</span>
						</a>
					  </div>
					</div>
		</section>