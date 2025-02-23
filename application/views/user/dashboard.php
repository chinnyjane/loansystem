<?php  
$lastcmc = $this->Cashmodel->lastcmctransaction(); 
$row = $lastcmc->first_row();
$lastcmcv = $this->Cashmodel->lastverfiedCMC(); 
if($lastcmcv->num_rows() > 0) {
$rowv = $lastcmcv->first_row();
}
if( $this->auth->perms("Loans.Loans Overview", $this->auth->user_id(), 2) == true){ 
 //$this->load->view('loans/loanstatus');
}
if( $this->auth->perms("CMC All Branches", $this->auth->user_id(), 2) == true){  ?>
<div>
	
	<h4 class="heading-inline">Welcome <?php echo $this->auth->firstname();?>!</h4>
		<!--&nbsp;&nbsp;<small>For the week of Jun 15 - Jun 22, 2011</small>
		&nbsp;&nbsp;</h4>
		
				<div class="btn-group ">
				  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
					<i class="fa fa-clock-o"></i>  &nbsp;
				    Change Week <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu">
		            <li><a href="javascript:;">Action</a></li>
		            <li><a href="javascript:;">Another action</a></li>
		            <li><a href="javascript:;">Something else here</a></li>
		            <li class="divider"></li>
		            <li><a href="javascript:;">Separated link</a></li>
		          </ul>
				</div> -->
			</div>

			<br>


			<div class="row">

				<div class="col-md-3 col-sm-6">

					<a href="<?php echo base_url();?>loans/status/granted" class="dashboard-stat primary">
						<div class="visual">
							<i class="fa fa-money"></i>
						</div> <!-- /.visual -->

						<div class="details">
							<span class="content">Loans Granted</span>
							<span class="value"><?php echo $this->loansetup->loancount('granted');?></span>
						</div> <!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div> <!-- /.col-md-3 -->
				
				<div class="col-md-3 col-sm-6">

					<a href="<?php echo base_url();?>loans/status/approved" class="dashboard-stat tertiary">
						<div class="visual">
							<i class="fa fa-star"></i>
						</div> <!-- /.visual -->

						<div class="details">
							<span class="content">Approved</span>
							<span class="value"><?php echo $this->loansetup->loancount('approved');?></span>
						</div> <!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div> <!-- /.col-md-9 -->

				<div class="col-md-3 col-sm-6">

					<a href="<?php echo base_url();?>loans/status/approval" class="dashboard-stat secondary">
						<div class="visual">
							<i class="fa fa-check"></i>
						</div> <!-- /.visual -->

						<div class="details">
							<span class="content">For Approval</span>
							<span class="value"><?php echo $this->loansetup->loancount('approval');?></span>
						</div> <!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div> <!-- /.col-md-3 -->

				<div class="col-md-3 col-sm-6">

					<a href="<?php echo base_url();?>loans/status/processing" class="dashboard-stat ">
						<div class="visual">
							<i class="fa fa-clock-o"></i>
						</div> <!-- /.visual -->

						<div class="details">
							<span class="content">On Process</span>
							<span class="value"><?php echo $this->loansetup->loancount('processing');?></span>
						</div> <!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div> <!-- /.col-md-3 -->

				



			</div> <!-- /.row -->




			<div class="row">

				<div class="col-md-9">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-bar-chart-o"></i>
								Area Chart
							</h3>

						</div> <!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="pull-left">
								<div class="btn-group" data-toggle="buttons">
								  <label class="btn btn-sm btn-default">
								    <input type="radio" name="options" id="option1"> Day
								  </label>
								  <label class="btn btn-sm btn-default">
								    <input type="radio" name="options" id="option2"> Week
								  </label>
								  <label class="btn btn-sm btn-default active">
								    <input type="radio" name="options" id="option3"> Month
								  </label>
								</div>

								&nbsp;

								  <div class="btn-group">
								    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
								      Custom Date
								      <span class="caret"></span>
								    </button>
								    <ul class="dropdown-menu">
								      <li><a href="javascript:;">Dropdown link</a></li>
								      <li><a href="javascript:;">Dropdown link</a></li>
								    </ul>
								  </div>
								
							</div>

							<div class="pull-right">
								<div class="btn-group">
								  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
								    <i class="fa fa-cog"></i> &nbsp;&nbsp;<span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu pull-right" role="menu">
								    <li><a href="javascript:;">Action</a></li>
								    <li><a href="javascript:;">Another action</a></li>
								    <li><a href="javascript:;">Something else here</a></li>
								    <li class="divider"></li>
								    <li><a href="javascript:;">Separated link</a></li>
								  </ul>
								</div>
							</div>

							<div class="clear"></div>
							<hr>


							<div id="area-chart" class="chart-holder" style="height: 250px"><svg height="250" version="1.1" width="747" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="50.5" y="207" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan></text><path fill="none" stroke="#aaaaaa" d="M63,207H722" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="50.5" y="161.49826435246996" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.50607685246996" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">6,554</tspan></text><path fill="none" stroke="#aaaaaa" d="M63,161.49826435246996H722" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="50.5" y="115.99652870493992" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.504341204939919" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">13,108</tspan></text><path fill="none" stroke="#aaaaaa" d="M63,115.99652870493992H722" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="50.5" y="70.50173564753004" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.49392314753004" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">19,661</tspan></text><path fill="none" stroke="#aaaaaa" d="M63,70.50173564753004H722" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="50.5" y="25" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">26,215</tspan></text><path fill="none" stroke="#aaaaaa" d="M63,25H722" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="600.2891859052247" y="222" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2012</tspan></text><text x="308.0230862697448" y="222" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: normal; font-family: Arial;" font-size="12px"><tspan dy="4.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2011</tspan></text><path fill="#aaaaaa" stroke="#000000" d="M63,170.1140186915888C81.41676792223572,166.29559412550068,118.25030376670716,158.37149532710282,136.66707168894288,154.84032042723632C155.0838396111786,151.30914552736982,191.91737545565005,146.69863497413672,210.33414337788577,141.86461949265686C228.5507290400972,137.08314765771482,264.9839003645201,117.80510219888028,283.2004860267315,116.37837116154873C301.2168894289186,114.96731848726479,337.2496962332928,129.47581537287812,355.2660996354799,130.5134846461949C373.68286755771567,131.57421323669652,410.51640340218705,124.06555407209613,428.9331713244228,124.77196261682244C447.34993924665855,125.47837116154874,484.18347509113,148.70439857879722,502.60024301336574,136.16475300400532C520.8168286755772,123.76140792459161,557.25,31.05046728971962,575.4665856622114,25C593.6831713244228,18.949532710280373,630.1163426488457,78.88939934484594,648.3329283110571,87.76101468624833C666.7496962332929,96.73012030612766,703.5832320777643,94.2124165554072,722,96.36288384512683L722,207L63,207Z" stroke-width="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="#eacca3" stroke="#000000" d="M63,188.49105473965287C81.41676792223572,184.3150867823765,118.25030376670716,175.4450600801068,136.66707168894288,171.7871829105474C155.0838396111786,168.12930574098797,191.91737545565005,161.22795858959486,210.33414337788577,159.22803738317756C228.5507290400972,157.24985445074307,264.9839003645201,157.4830020137347,283.2004860267315,155.8747663551402C301.2168894289186,154.2842036158709,337.2496962332928,148.66353232881937,355.2660996354799,146.4328437917223C373.68286755771567,144.15258439824527,410.51640340218705,137.7363818424566,428.9331713244228,137.83097463284378C447.34993924665855,137.92556742323097,484.18347509113,156.85499937986532,502.60024301336574,147.18958611481975C520.8168286755772,137.62923168961163,557.25,66.59652870493991,575.4665856622114,60.9279038718291C593.6831713244228,55.25927903871828,630.1163426488457,95.88964010301531,648.3329283110571,101.84058744993324C666.7496962332929,107.85692982264148,703.5832320777643,107.05794392523364,722,108.79706275033378L722,207L63,207Z" stroke-width="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="#dc847a" stroke="#000000" d="M63,188.49105473965287C81.41676792223572,188.29666221628838,118.25030376670716,189.6626168224299,136.66707168894288,187.71348464619493C155.0838396111786,185.76435246995996,191.91737545565005,173.76096507547405,210.33414337788577,172.89799732977303C228.5507290400972,172.04440966826442,264.9839003645201,182.50349268638112,283.2004860267315,180.84726301735648C301.2168894289186,179.2092336743651,337.2496962332928,161.35428189967575,355.2660996354799,159.72096128170895C373.68286755771567,158.05134465000955,410.51640340218705,165.90854472630176,428.9331713244228,167.6355140186916C447.34993924665855,169.36248331108146,484.18347509113,181.74145344977276,502.60024301336574,173.53671562082778C520.8168286755772,165.42115972480613,557.25,107.4458611481976,575.4665856622114,102.3543391188251C593.6831713244228,97.2628170894526,630.1163426488457,127.07281475482795,648.3329283110571,132.80453938584782C666.7496962332929,138.59925000182395,703.5832320777643,144.54619492656877,722,148.46008010680907L722,207L63,207Z" stroke-width="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#888888" d="M63,170.1140186915888C81.41676792223572,166.29559412550068,118.25030376670716,158.37149532710282,136.66707168894288,154.84032042723632C155.0838396111786,151.30914552736982,191.91737545565005,146.69863497413672,210.33414337788577,141.86461949265686C228.5507290400972,137.08314765771482,264.9839003645201,117.80510219888028,283.2004860267315,116.37837116154873C301.2168894289186,114.96731848726479,337.2496962332928,129.47581537287812,355.2660996354799,130.5134846461949C373.68286755771567,131.57421323669652,410.51640340218705,124.06555407209613,428.9331713244228,124.77196261682244C447.34993924665855,125.47837116154874,484.18347509113,148.70439857879722,502.60024301336574,136.16475300400532C520.8168286755772,123.76140792459161,557.25,31.05046728971962,575.4665856622114,25C593.6831713244228,18.949532710280373,630.1163426488457,78.88939934484594,648.3329283110571,87.76101468624833C666.7496962332929,96.73012030612766,703.5832320777643,94.2124165554072,722,96.36288384512683" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#f0ad4e" d="M63,188.49105473965287C81.41676792223572,184.3150867823765,118.25030376670716,175.4450600801068,136.66707168894288,171.7871829105474C155.0838396111786,168.12930574098797,191.91737545565005,161.22795858959486,210.33414337788577,159.22803738317756C228.5507290400972,157.24985445074307,264.9839003645201,157.4830020137347,283.2004860267315,155.8747663551402C301.2168894289186,154.2842036158709,337.2496962332928,148.66353232881937,355.2660996354799,146.4328437917223C373.68286755771567,144.15258439824527,410.51640340218705,137.7363818424566,428.9331713244228,137.83097463284378C447.34993924665855,137.92556742323097,484.18347509113,156.85499937986532,502.60024301336574,147.18958611481975C520.8168286755772,137.62923168961163,557.25,66.59652870493991,575.4665856622114,60.9279038718291C593.6831713244228,55.25927903871828,630.1163426488457,95.88964010301531,648.3329283110571,101.84058744993324C666.7496962332929,107.85692982264148,703.5832320777643,107.05794392523364,722,108.79706275033378" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#e5412d" d="M63,188.49105473965287C81.41676792223572,188.29666221628838,118.25030376670716,189.6626168224299,136.66707168894288,187.71348464619493C155.0838396111786,185.76435246995996,191.91737545565005,173.76096507547405,210.33414337788577,172.89799732977303C228.5507290400972,172.04440966826442,264.9839003645201,182.50349268638112,283.2004860267315,180.84726301735648C301.2168894289186,179.2092336743651,337.2496962332928,161.35428189967575,355.2660996354799,159.72096128170895C373.68286755771567,158.05134465000955,410.51640340218705,165.90854472630176,428.9331713244228,167.6355140186916C447.34993924665855,169.36248331108146,484.18347509113,181.74145344977276,502.60024301336574,173.53671562082778C520.8168286755772,165.42115972480613,557.25,107.4458611481976,575.4665856622114,102.3543391188251C593.6831713244228,97.2628170894526,630.1163426488457,127.07281475482795,648.3329283110571,132.80453938584782C666.7496962332929,138.59925000182395,703.5832320777643,144.54619492656877,722,148.46008010680907" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><circle cx="63" cy="170.1140186915888" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="136.66707168894288" cy="154.84032042723632" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="210.33414337788577" cy="141.86461949265686" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="283.2004860267315" cy="116.37837116154873" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="355.2660996354799" cy="130.5134846461949" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="428.9331713244228" cy="124.77196261682244" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="502.60024301336574" cy="136.16475300400532" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="575.4665856622114" cy="25" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="648.3329283110571" cy="87.76101468624833" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="722" cy="96.36288384512683" r="3" fill="#888888" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="63" cy="188.49105473965287" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="136.66707168894288" cy="171.7871829105474" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="210.33414337788577" cy="159.22803738317756" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="283.2004860267315" cy="155.8747663551402" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="355.2660996354799" cy="146.4328437917223" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="428.9331713244228" cy="137.83097463284378" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="502.60024301336574" cy="147.18958611481975" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="575.4665856622114" cy="60.9279038718291" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="648.3329283110571" cy="101.84058744993324" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="722" cy="108.79706275033378" r="3" fill="#f0ad4e" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="63" cy="188.49105473965287" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="136.66707168894288" cy="187.71348464619493" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="210.33414337788577" cy="172.89799732977303" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="283.2004860267315" cy="180.84726301735648" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="355.2660996354799" cy="159.72096128170895" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="428.9331713244228" cy="167.6355140186916" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="502.60024301336574" cy="173.53671562082778" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="575.4665856622114" cy="102.3543391188251" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="648.3329283110571" cy="132.80453938584782" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="722" cy="148.46008010680907" r="3" fill="#e5412d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle></svg><div class="morris-hover morris-default-style" style="left: 5px; top: 46.1140186915888px; display: none;"><div class="morris-hover-row-label">2010 Q1</div><div class="morris-hover-point" style="color: #e5412d">
  iPhone:
  2,666
</div><div class="morris-hover-point" style="color: #f0ad4e">
  iPad:
  -
</div><div class="morris-hover-point" style="color: #888">
  iPod Touch:
  2,647
</div></div></div> <!-- /#bar-chart -->

						</div> <!-- /.portlet-content -->

					</div> <!-- /.portlet -->

					<div class="row">

					<div class="col-md-6">

						<div class="portlet">

							<div class="portlet-header">

								<h3>
									<i class="fa fa-money"></i>
									Recent Loans
								</h3>

								<ul class="portlet-tools pull-right">
									<li>
										<button class="btn btn-sm btn-default">
											Add Loan
										</button>
									</li>
								</ul>

							</div> <!-- /.portlet-header -->

							<div class="portlet-content">

								<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Disbursed On</th>
											
											<th>Amount</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>09/21/2015</td>
											
											<td>Php 108.35</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
										<tr>
											<td>09/21/2015</td>
											
											<td>Php 30.89</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
										<tr>
											<td>09/20/2015</td>
											
											<td>Php 52.06</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
										<tr>
											<td>09/19/2015</td>
											
											<td>Php 73.54</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
										<tr>
											<td>09/19/2013</td>
											
											<td>Php 73.54</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
										<tr>
											<td>09/19/2013</td>
											
											<td> Php 73.54</td>
											<td><a href="javascript:;" class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></td>
										</tr>
									</tbody>
								</table>
							</div> <!-- /.table-responsive -->

								<hr>

								<a href="javascript:;" class="btn btn-sm btn-secondary">View All Loans</a>
								

							</div> <!-- /.portlet-content -->

						</div> <!-- /.portlet -->


					</div> <!-- /.col-md-4 -->



					<div class="col-md-6">

						<div class="portlet">

							<div class="portlet-header">

								<h3>
									<i class="fa fa-group"></i>
									Recent Clients
								</h3>

								<ul class="portlet-tools pull-right">
									<li>
										<button class="btn btn-sm btn-default">
											Add User
										</button>
									</li>
								</ul>

							</div> <!-- /.portlet-header -->

							<div class="portlet-content">


								<div class="table-responsive">

								<table id="user-signups" class="table table-striped table-checkable"> 
									<thead> 
										<tr> 
											<th class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" id="check-all" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
											</th> 
											<th class="hidden-xs">First Name
											</th> 
											<th>Last Name</th> 
											<th>Status
											</th> 

											<th class="align-center">Approve
											</th> 

										</tr> 
									</thead> 

									<tbody> 
										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" name="actiony" value="joey" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> 
											</td> 

											<td class="hidden-xs">Joey</td> 
											<td>Greyson</td> 
											<td><span class="label label-success">Approved</span></td> 
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td> 
										</tr> 

										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" name="actiony" value="wolf" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
											</td> 
											<td class="hidden-xs">Wolf</td> 
											<td>Bud</td> <td><span class="label label-default">Pending</span>
											</td>  
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td> 
										</tr> 


										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" name="actiony" value="sam" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> 
											</td> 

											<td class="hidden-xs">Sam</td> 
											<td>Mitchell</td> 
											<td><span class="label label-success">Approved</span></td>  
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td> 
										</tr> 
										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" value="carlos" name="actiony" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> 
											</td> 
											<td class="hidden-xs">Carlos</td> 
											<td>Lopez</td> 
											<td><span class="label label-success">Approved</span></td> 
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td>  
										</tr>  




										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" name="actiony" value="rob" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> 
											</td> 
											<td class="hidden-xs">Rob</td> 
											<td>Johnson</td> 
											<td><span class="label label-default">Pending</span></td> 
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td> 
										</tr> 
										<tr class=""> 
											<td class="checkbox-column"> 
												<div class="icheckbox_minimal-blue icheck-input" style="position: relative;"><input type="checkbox" value="mike" name="actiony" class="icheck-input" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> 
											</td> 
											<td class="hidden-xs">Mike</td> 
											<td>Jones</td> 
											<td><span class="label label-default">Pending</span></td>  
											<td class="align-center">
												<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
													<i class="fa fa-check"></i>
												</a> 
											</td> 
										</tr>										

									</tbody> 
								</table>
										

								</div> <!-- /.table-responsive -->

								<hr>

								Apply to Selected: &nbsp;&nbsp;
								<div class="select2-container ui-select2" id="s2id_apply-selected" style="width: 125px"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">Select Action</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" type="text" id="s2id_autogen1"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input">   </div>   <ul class="select2-results">   </ul></div></div><select id="apply-selected" name="table-select" class="ui-select2 select2-offscreen" style="width: 125px" tabindex="-1">
									<option value="">Select Action</option>
									<option value="approve">Approve</option>
									<option value="edit">Edit</option>
									<option value="delete">Delete</option>
									
								</select>
								
							</div> <!-- /.portlet-content -->

						</div> <!-- /.portlet -->

					</div> <!-- /.col-md-4 -->


				</div> <!-- /.row -->






				<div class="portlet">

					<div class="portlet-header">

						<h3>
							<i class="fa fa-calendar"></i>
							Full Calendar
						</h3>

					</div> <!-- /.portlet-header -->

					<div class="portlet-content">


							<div id="full-calendar" class="fc fc-ltr"><table class="fc-header" style="width:100%"><tbody><tr><td class="fc-header-left"><span class="fc-button fc-button-prev fc-state-default fc-corner-left" unselectable="on"><span class="fc-text-arrow">‹</span></span><span class="fc-button fc-button-next fc-state-default fc-corner-right" unselectable="on"><span class="fc-text-arrow">›</span></span></td><td class="fc-header-center"><span class="fc-header-title"><h2>August 2014</h2></span></td><td class="fc-header-right"><span class="fc-button fc-button-month fc-state-default fc-corner-left fc-state-active" unselectable="on">month</span><span class="fc-button fc-button-agendaWeek fc-state-default" unselectable="on">week</span><span class="fc-button fc-button-agendaDay fc-state-default fc-corner-right" unselectable="on">day</span></td></tr></tbody></table><div class="fc-content" style="position: relative;"><div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on"><div class="fc-event-container" style="position:absolute;z-index:8;top:0;left:0"><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-red" style="position: absolute; left: 533px; width: 211px; top: 48px;"><div class="fc-event-inner"><span class="fc-event-title">All Day Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-yellow" style="position: absolute; left: 533px; width: 213px; top: 73px;"><div class="fc-event-inner"><span class="fc-event-title">Long Event</span></div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-end fc-yellow" style="position: absolute; left: 1px; width: 209px; top: 137px;"><div class="fc-event-inner"><span class="fc-event-title">Long Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-grey" style="position: absolute; left: 3px; width: 101px; top: 162px;"><div class="fc-event-inner"><span class="fc-event-time">4p</span><span class="fc-event-title">Repeating Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-charcoal" style="position: absolute; left: 427px; width: 317px; top: 137px;"><div class="fc-event-inner"><span class="fc-event-time">2p</span><span class="fc-event-title">Repeating Event</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div><div class="fc-event fc-event-hori fc-event-draggable fc-event-start fc-event-end fc-red" style="position: absolute; left: 321px; width: 101px; top: 137px;"><div class="fc-event-inner"><span class="fc-event-time">12p</span><span class="fc-event-title">Lunch</span></div><div class="ui-resizable-handle ui-resizable-e">&nbsp;&nbsp;&nbsp;</div></div></div><table class="fc-border-separate" style="width:100%" cellspacing="0"><thead><tr class="fc-first fc-last"><th class="fc-day-header fc-sun fc-widget-header fc-first" style="width: 106px;">Sun</th><th class="fc-day-header fc-mon fc-widget-header" style="width: 106px;">Mon</th><th class="fc-day-header fc-tue fc-widget-header" style="width: 106px;">Tue</th><th class="fc-day-header fc-wed fc-widget-header" style="width: 106px;">Wed</th><th class="fc-day-header fc-thu fc-widget-header" style="width: 106px;">Thu</th><th class="fc-day-header fc-fri fc-widget-header" style="width: 106px;">Fri</th><th class="fc-day-header fc-sat fc-widget-header fc-last">Sat</th></tr></thead><tbody><tr class="fc-week fc-first"><td class="fc-day fc-sun fc-widget-content fc-other-month fc-past fc-first" data-date="2014-07-27"><div style="min-height: 88px;"><div class="fc-day-number">27</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-other-month fc-past" data-date="2014-07-28"><div><div class="fc-day-number">28</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-other-month fc-past" data-date="2014-07-29"><div><div class="fc-day-number">29</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-other-month fc-past" data-date="2014-07-30"><div><div class="fc-day-number">30</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-other-month fc-past" data-date="2014-07-31"><div><div class="fc-day-number">31</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-past" data-date="2014-08-01"><div><div class="fc-day-number">1</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-past fc-last" data-date="2014-08-02"><div><div class="fc-day-number">2</div><div class="fc-day-content"><div style="position: relative; height: 50px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-past fc-first" data-date="2014-08-03"><div style="min-height: 87px;"><div class="fc-day-number">3</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-past" data-date="2014-08-04"><div><div class="fc-day-number">4</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-past" data-date="2014-08-05"><div><div class="fc-day-number">5</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-today fc-state-highlight" data-date="2014-08-06"><div><div class="fc-day-number">6</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2014-08-07"><div><div class="fc-day-number">7</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2014-08-08"><div><div class="fc-day-number">8</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2014-08-09"><div><div class="fc-day-number">9</div><div class="fc-day-content"><div style="position: relative; height: 72px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2014-08-10"><div style="min-height: 87px;"><div class="fc-day-number">10</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2014-08-11"><div><div class="fc-day-number">11</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2014-08-12"><div><div class="fc-day-number">12</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2014-08-13"><div><div class="fc-day-number">13</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2014-08-14"><div><div class="fc-day-number">14</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2014-08-15"><div><div class="fc-day-number">15</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2014-08-16"><div><div class="fc-day-number">16</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2014-08-17"><div style="min-height: 87px;"><div class="fc-day-number">17</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2014-08-18"><div><div class="fc-day-number">18</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2014-08-19"><div><div class="fc-day-number">19</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2014-08-20"><div><div class="fc-day-number">20</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2014-08-21"><div><div class="fc-day-number">21</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2014-08-22"><div><div class="fc-day-number">22</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2014-08-23"><div><div class="fc-day-number">23</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2014-08-24"><div style="min-height: 87px;"><div class="fc-day-number">24</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-future" data-date="2014-08-25"><div><div class="fc-day-number">25</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-future" data-date="2014-08-26"><div><div class="fc-day-number">26</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-future" data-date="2014-08-27"><div><div class="fc-day-number">27</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-future" data-date="2014-08-28"><div><div class="fc-day-number">28</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-future" data-date="2014-08-29"><div><div class="fc-day-number">29</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-future fc-last" data-date="2014-08-30"><div><div class="fc-day-number">30</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr><tr class="fc-week fc-last"><td class="fc-day fc-sun fc-widget-content fc-future fc-first" data-date="2014-08-31"><div style="min-height: 88px;"><div class="fc-day-number">31</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-mon fc-widget-content fc-other-month fc-future" data-date="2014-09-01"><div><div class="fc-day-number">1</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-tue fc-widget-content fc-other-month fc-future" data-date="2014-09-02"><div><div class="fc-day-number">2</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-wed fc-widget-content fc-other-month fc-future" data-date="2014-09-03"><div><div class="fc-day-number">3</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-thu fc-widget-content fc-other-month fc-future" data-date="2014-09-04"><div><div class="fc-day-number">4</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-fri fc-widget-content fc-other-month fc-future" data-date="2014-09-05"><div><div class="fc-day-number">5</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td><td class="fc-day fc-sat fc-widget-content fc-other-month fc-future fc-last" data-date="2014-09-06"><div><div class="fc-day-number">6</div><div class="fc-day-content"><div style="position: relative; height: 0px;">&nbsp;</div></div></div></td></tr></tbody></table></div></div></div>


					</div> <!-- /.portlet-content -->

				</div> <!-- /.portlet -->



				</div> <!-- /.col-md-9 -->




				<div class="col-md-3">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-bar-chart-o"></i>
								Donut Chart
							</h3>

						</div> <!-- /.portlet-header -->

						<div class="portlet-content">

							<div id="donut-chart" class="chart-holder" style="height: 250px"><svg height="250" version="1.1" width="208" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative; left: -0.25px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#e5412d" d="M104,187.66666666666666A62.666666666666664,62.666666666666664,0,0,0,166.6666664733869,125.00492182848556" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#e5412d" stroke="#ffffff" d="M104,190.66666666666666A65.66666666666666,65.66666666666666,0,0,0,169.66666646413415,125.00515744793434L192.9999997255016,125.00699004364705A89,89,0,0,1,104,214Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#f0ad4e" d="M166.6666664733869,125.00492182848556A62.666666666666664,62.666666666666664,0,0,0,55.13037676772361,85.77155322668943" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#f0ad4e" stroke="#ffffff" d="M169.66666646413415,125.00515744793434A65.66666666666666,65.66666666666666,0,0,0,52.7908735278806,83.89359566839265L30.695565151585413,66.15732984003414A94,94,0,0,1,197.99999971008037,125.00738274272834Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#444444" d="M55.13037676772361,85.77155322668943A62.666666666666664,62.666666666666664,0,0,0,64.76771513645053,173.86654208654846" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#444444" stroke="#ffffff" d="M52.7908735278806,83.89359566839265A65.66666666666666,65.66666666666666,0,0,0,62.88957383979126,176.2058978247343L48.28180819910794,194.400886899513A89,89,0,0,1,34.59473721799044,69.28725910386211Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#888888" d="M64.76771513645053,173.86654208654846A62.666666666666664,62.666666666666664,0,0,0,103.9803126863613,187.66666357419058" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#888888" stroke="#ffffff" d="M62.88957383979126,176.2058978247343A65.66666666666666,65.66666666666666,0,0,0,103.97937020858073,190.6666634261465L103.97203982584291,213.99999560802598A89,89,0,0,1,48.28180819910794,194.400886899513Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="104" y="115" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: 800; font-size: 15px; line-height: normal; font-family: Arial;" font-size="15px" font-weight="800" transform="matrix(1.7091,0,0,1.7091,-73.7455,-87.9273)" stroke-width="0.5851063829787234"><tspan dy="5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Referrals</tspan></text><text x="104" y="135" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: normal; font-family: Arial;" font-size="14px" transform="matrix(1.3056,0,0,1.3056,-31.7778,-38.8056)" stroke-width="0.7659574468085106"><tspan dy="5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">40%</tspan></text></svg></div>
							

						</div> <!-- /.portlet-content -->

					</div> <!-- /.portlet -->



					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-compass"></i>
								Traffic Overview
							</h3>

						</div> <!-- /.portlet-header -->

						<div class="portlet-content">


							<div class="progress-stat">
							
								<div class="stat-header">
									
									<div class="stat-label">
										% New Visits
									</div> <!-- /.stat-label -->
									
									<div class="stat-value">
										77.7%
									</div> <!-- /.stat-value -->
									
								</div> <!-- /stat-header -->
								
								<div class="progress progress-striped active">
								  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="77" aria-valuemin="0" aria-valuemax="100" style="width: 77%">
								    <span class="sr-only">77.74% Visit Rate</span>
								  </div>
								</div> <!-- /.progress -->
								
							</div> <!-- /.progress-stat -->

							<div class="progress-stat">
							
								<div class="stat-header">
									
									<div class="stat-label">
										% Mobile Visitors
									</div> <!-- /.stat-label -->
									
									<div class="stat-value">
										33.2%
									</div> <!-- /.stat-value -->
									
								</div> <!-- /stat-header -->
								
								<div class="progress progress-striped active">
								  <div class="progress-bar progress-bar-tertiary" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
								    <span class="sr-only">33% Mobile Visitors</span>
								  </div>
								</div> <!-- /.progress -->
								
							</div> <!-- /.progress-stat -->

							<div class="progress-stat">
							
								<div class="stat-header">
									
									<div class="stat-label">
										Bounce Rate
									</div> <!-- /.stat-label -->
									
									<div class="stat-value">
										42.7%
									</div> <!-- /.stat-value -->
									
								</div> <!-- /stat-header -->
								
								<div class="progress progress-striped active">
								  <div class="progress-bar progress-bar-secondary" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width: 42%">
								    <span class="sr-only">42.7% Bounce Rate</span>
								  </div>
								</div> <!-- /.progress -->
								
							</div> <!-- /.progress-stat -->

							<br>						

						</div> <!-- /.portlet-content -->

					</div> <!-- /.portlet -->




					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-bar-chart-o"></i>
								Sparkline
							</h3>

						</div> <!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="row row-marginless">

								<div class="spark-stat col-md-6 col-sm-6 col-xs-6">

									<div id="total-visits"><canvas width="54" height="35" style="display: inline-block; width: 54px; height: 35px; vertical-align: top;"></canvas></div>
									<span class="value">1,564</span>
									<h5>Total Visits</h5>

								</div> <!-- /.col -->

								<div class="spark-stat col-md-6 col-sm-6 col-xs-6">

									<div id="new-visits"><canvas width="54" height="35" style="display: inline-block; width: 54px; height: 35px; vertical-align: top;"></canvas></div>
									<span class="value">872</span>
									<h5>New Visits</h5>

								</div> <!-- /.col -->

							</div> <!-- /.row -->


							<div class="row row-marginless">

								<div class="spark-stat col-md-6 col-sm-6 col-xs-6">

									<div id="unique-visits"><canvas width="54" height="35" style="display: inline-block; width: 54px; height: 35px; vertical-align: top;"></canvas></div>
									<span class="value">845</span>
									<h5>Unique Visits</h5>

								</div> <!-- /.col -->

								<div class="spark-stat col-md-6 col-sm-6 col-xs-6">

									<div id="revenue-visits" data-bar-color="#c00"><canvas width="54" height="35" style="display: inline-block; width: 54px; height: 35px; vertical-align: top;"></canvas></div>
									<span class="value">$13,492</span>
									<h5>Revenue Visits</h5>

								</div> <!-- /.col -->

							</div> <!-- /.row -->

						</div> <!-- /.portlet-content -->

					</div> <!-- /.portlet -->

				</div> <!-- /.col -->

			</div> <!-- /.row -->

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-green">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Consolidated Cash Movement Graph</h3>
        </div>
        <div class="panel-body">
            <div id="cmc_monthly"></div>
        </div>
    </div>
  </div>
</div>
     <div class="row">
	<div class="col-lg-6">
        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i>Consolidated CMC Pie Chart</h3>
                            </div>
                            <div class="panel-body">
                                <div class="flot-chart">
                                    <div class="flot-chart-content" id="pie-chart"></div>
                                </div>
                                <div class="text-right">
                                    <a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
   <?php } else { ?>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-yellow">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $this->auth->branchname();?> Cash Movement Graph</h3>
      </div>
      <div class="panel-body">
        <div id="branchcmc_monthly"></div>
      </div>
    </div>
  </div>
</div>
     <?php } ?>

<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-info">
					<div class="panel-heading">Last CMC Transactions</div>
					<div class="table-responsive"><?php 
					$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover table-condensed">');
					$this->table->set_template($tmpl);
					
					if($lastcmc->num_rows() > 0){
						$this->table->set_heading( "Date & Time Posted","Branch", "Type", "Posted by" );
						foreach($lastcmc->result() as $cmc){
							$amount = ($cmc->Amount_IN) ? $cmc->Amount_IN : $cmc->Amount_OUT;
							 switch($cmc->transCatName){
								case "collection":
									$ref = "OR # ".$cmc->referenceNo;
								break;
								case "disbursement":
									$ref = "CV # ".$cmc->referenceNo;
								break;
								case "adjustment":
									$ref = "JV # ".$cmc->referenceNo;
								break;
							 }
							$this->table->add_row($cmc->dateAdded, $cmc->branchname,  $cmc->transType, $cmc->username);
						}
						echo $this->table->generate();
					}else{
						echo "No transaction was posted.";
					}
					
					?>
					</div>
				<a href="">
					<div class="panel-footer">
						<span class="pull-left">View Details</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</a>
			</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-warning">
					<div class="panel-heading">Last Verified Transactions</div>
					<div class="table-responsive"><?php 
					$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover table-condensed">');
					$this->table->set_template($tmpl);
					$lastcmc = $this->Cashmodel->lastverfiedCMC(); 
					if($lastcmc->num_rows() > 0){
						$this->table->set_heading("Date Verified","Branch","Reference No.", "Type", "Verified by" );
						foreach($lastcmc->result() as $cmc){
							$amount = ($cmc->Amount_IN) ? $cmc->Amount_IN : $cmc->Amount_OUT;
							 switch($cmc->transCatName){
								case "collection":
									$ref = "OR # ".$cmc->referenceNo;
								break;
								case "disbursement":
									$ref = "CV # ".$cmc->referenceNo;
								break;
								case "adjustment":
									$ref = "JV # ".$cmc->referenceNo;
								break;
							 }
							$this->table->add_row( $cmc->dateVerified, $cmc->branchname, $ref,  $cmc->transType, $cmc->username);
						}
						echo $this->table->generate();
					}else{
						echo "No transaction was posted.";
					}					
					?>
					</div>
				</div>
	</div>
</div>

				<div class="flot-chart" style="display:none">
                     <div class="flot-chart-content" id="flot-line-chart"></div>
                </div>
                <div class="row">
                   <div id="morris-area-chart"  class="hide"></div>
                   <div id="morris-donut-chart"  class="hide"></div>
                     
                    <div id="morris-line-chart" class="hide"></div>
					<div id="morris-bar-chart" class="hide"></div>
                </div>
                <!-- /.row -->

<?php
	$year = date("Y");
	$cash = $this->Cashmodel->ycfccashsummary($year, 'all');
	$data = array();
	foreach($cash->result() as $c){
		$data[] = array("period"=>$c->month,
            "Collections"=>number_format($c->col,2,'.',''),
            "Disbursement"=>number_format($c->dis,2,'.',''),
            "Adjustment"=>number_format($c->adj,2,'.',''));
	}
	
	$cash = $this->Cashmodel->ycfccashsummary($year, $this->auth->branch_id());
	$data3 = array();
	foreach($cash->result() as $c){
		$data3[] = array("period"=>$c->month,
            "Collections"=>number_format($c->col,2,'.',''),
            "Disbursement"=>number_format($c->dis,2,'.',''),
            "Adjustment"=>number_format($c->adj,2,'.',''));
	}
	
	
	$current = $this->Cashmodel->currentstatus();
	$data2 = array();
	$total = 0;
	foreach($current->result() as $c){
		$total += $c->EndingBal;		
	}
	foreach($current->result() as $c){
		$val = $c->EndingBal/$total * 10;
		$data2[] = array("label"=>$c->branchname,
            "data"=>$val);
	}
?>