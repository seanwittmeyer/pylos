	<!-- Jumbotron -->
	<div class="container-fluid build-wrapper">
		<div class="row">
			<div class="col-md-6">
				<div class="bs-component">
					<div class="jumbotron jumbotron-home windowheight" id="imgheader" style="background-image: url('http://photos.seanwittmeyer.com/images/France/Montpillier/swp.france.656.jpg');">
						<div class="container">
							<h1 class="light-text">Lyon<!-- (3)--></h1>
							<p class="light-text">This is a 13 day trip to the South of France with a variety of people spanning from June 13 through the 25th.</p>
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="bs-component">
					<div class="trip-itinerary" id="imgheader" style="background-color: #f9f5eb">
						<div class="map-container" class="windowheight" style="height: 350px;">


							<style>
							    .mapboxgl-popup {
							        max-width: 400px;
							        /* font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif; */
							    }
							</style>
							<div id='builder-mapbox-map' ></div>
							<script>
							mapboxgl.accessToken = 'pk.eyJ1Ijoic2VhbndpdHRtZXllciIsImEiOiJjamhiaWlmZ2MwMm4yM2RxbzNxcmJwdTB4In0.h6Gl0fZHDBPfNqD6Ltw_Bw';
							
							var map = new mapboxgl.Map({
							    container: 'builder-mapbox-map',
							    style: 'mapbox://styles/seanwittmeyer/cjhcez82n11sk2rpa58x40u2p',
							    center: [-77.04, 38.907],
							    zoom: 11.15
							});
							
							map.on('load', function() {
							
							    // Add a layer showing the places.
							    map.addLayer({
							        "id": "places",
							        "type": "symbol",
							        "source": {
							            "type": "geojson",
							            "data": {
							                "type": "FeatureCollection",
							                "features": [{
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Make it Mount Pleasant</strong><p>Make it Mount Pleasant is a handmade and vintage market and afternoon of live entertainment and kids activities. 12:00-6:00 p.m.</p>",
							                        "icon": "theatre"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.038659, 38.931567]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Mad Men Season Five Finale Watch Party</strong><p>Head to Lounge 201 (201 Massachusetts Avenue NE) Sunday for a Mad Men Season Five Finale Watch Party, complete with 60s costume contest, Mad Men trivia, and retro food and drink. 8:00-11:00 p.m. $10 general admission, $20 admission and two hour open bar.</p>",
							                        "icon": "theatre"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.003168, 38.894651]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Big Backyard Beach Bash and Wine Fest</strong><p>EatBar (2761 Washington Boulevard Arlington VA) is throwing a Big Backyard Beach Bash and Wine Fest on Saturday, serving up conch fritters, fish tacos and crab sliders, and Red Apron hot dogs. 12:00-3:00 p.m. $25.grill hot dogs.</p>",
							                        "icon": "bar"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.090372, 38.881189]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Ballston Arts & Crafts Market</strong><p>The Ballston Arts & Crafts Market sets up shop next to the Ballston metro this Saturday for the first of five dates this summer. Nearly 35 artists and crafters will be on hand selling their wares. 10:00-4:00 p.m.</p>",
							                        "icon": "art-gallery"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.111561, 38.882342]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Seersucker Bike Ride and Social</strong><p>Feeling dandy? Get fancy, grab your bike, and take part in this year's Seersucker Social bike ride from Dandies and Quaintrelles. After the ride enjoy a lawn party at Hillwood with jazz, cocktails, paper hat-making, and more. 11:00-7:00 p.m.</p>",
							                        "icon": "bicycle"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.052477, 38.943951]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Capital Pride Parade</strong><p>The annual Capital Pride Parade makes its way through Dupont this Saturday. 4:30 p.m. Free.</p>",
							                        "icon": "star"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.043444, 38.909664]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Muhsinah</strong><p>Jazz-influenced hip hop artist Muhsinah plays the Black Cat (1811 14th Street NW) tonight with Exit Clov and Gods’illa. 9:00 p.m. $12.</p>",
							                        "icon": "music"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.031706, 38.914581]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>A Little Night Music</strong><p>The Arlington Players' production of Stephen Sondheim's <em>A Little Night Music</em> comes to the Kogod Cradle at The Mead Center for American Theater (1101 6th Street SW) this weekend and next. 8:00 p.m.</p>",
							                        "icon": "music"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.020945, 38.878241]
							                    }
							                }, {
							                    "type": "Feature",
							                    "properties": {
							                        "description": "<strong>Truckeroo</strong><p>Truckeroo brings dozens of food trucks, live music, and games to half and M Street SE (across from Navy Yard Metro Station) today from 11:00 a.m. to 11:00 p.m.</p>",
							                        "icon": "music"
							                    },
							                    "geometry": {
							                        "type": "Point",
							                        "coordinates": [-77.007481, 38.876516]
							                    }
							                }]
							            }
							        },
							        "layout": {
							            "icon-image": "{icon}-15",
							            "icon-allow-overlap": true
							        }
							    });
							
							    // Create a popup, but don't add it to the map yet.
							    var popup = new mapboxgl.Popup({
							        closeButton: false,
							        closeOnClick: false
							    });
							
							    map.on('mouseenter', 'places', function(e) {
							        // Change the cursor style as a UI indicator.
							        map.getCanvas().style.cursor = 'pointer';
							
							        var coordinates = e.features[0].geometry.coordinates.slice();
							        var description = e.features[0].properties.description;
							
							        // Ensure that if the map is zoomed out such that multiple
							        // copies of the feature are visible, the popup appears
							        // over the copy being pointed to.
							        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
							            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
							        }
							
							        // Populate the popup and set its coordinates
							        // based on the feature found.
							        popup.setLngLat(coordinates)
							            .setHTML(description)
							            .addTo(map);
							    });
							
							    map.on('mouseleave', 'places', function() {
							        map.getCanvas().style.cursor = '';
							        popup.remove();
							    });
							});
							</script>
						</div><!-- .map-container -->
					</div>
				</div>
				<div class="bs-component">
					<div class="trip-itinerary" id="imgheader" >



						<div class="itinerary-header">
							<h3 class="light-text">Trip Itinerary</h3>
							<p class="light-text">Looks like we are headed out in 32 days. You can add and modify items in this itinerary below. You can also archive it and add a photo gallery.</p>
						</div>
						<div class="itinerary-day">
							<div class="pull-right"><a href="/taxonomy/" class="btn btn-primary btn-xs" data-toggle="tooltip" data-title="Visit the category and make changes from the edit menu"><span class=""></span>View &amp; Edit</a> <a href="/api/" class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Are you sure? No going back..."><span class=""></span>Delete</a></div>
							<div class="panel panel-info">
								<h2 class="itinerary-header">Wednesday<small>June 13</small></h2>
								<div class="alert alert-success" role="alert"><h1>Portland</h1></div>
								<div class="bs-callout bs-callout-info bs-callout-overnight">
									<!--<h3>Flight to Lyon</h3>-->
									<div class="row">
										<div class="col-xs-3 col-sm-1"><img class="img-airline" src="/includes/img/airlines/LX.png" /></div>
										<div class="col-sm-6"><p class="icon">PDX &rarr; SFO</p></div>
										<div class="col-sm-4"><p><strong>UA 2348 - ODUFGO</strong><br />11:50 AM &rarr; 1:14 PM</p></div>
									</div>
									<p>1 hour 46 minute layover</p>
									<div class="row">
										<div class="col-xs-3 col-sm-1"><img class="img-airline" src="/includes/img/airlines/SK.png" /></div>
										<div class="col-sm-6"><p class="icon">SFO &rarr; FRA</p></div>
										<div class="col-sm-4"><p><strong>LH 455 - ODUFGO</strong><br />3:00 PM &rarr; 10:55 AM</p></div>
									</div>
									
									<p>1 hour 30 minute layover</p>
									<div class="row">
										<div class="col-xs-3 col-sm-1"><img class="img-airline" src="/includes/img/airlines/LH.png" /></div>
										<div class="col-sm-6"><p class="icon">FRA &rarr; LYS</p></div>
										<div class="col-sm-4"><p><strong>LH 1076 - ODUFGO</strong><br />12:20 PM &rarr; 1:35 PM</p></div>
									</div>
								</div>
							</div>
							<div class="panel panel-info panel-overnight">
								<h2 class="itinerary-header">Thursday<small>June 14</small></h2>
								<div class="alert alert-success" role="alert"><h1>Lyon</h1></div>
								<div class="bs-callout bs-callout-warning">
									<div class="row">
										<div class="col-sm-1"><p class="icon">🚆</p></div>
										<!--<div class="col-sm-3"><p>11:50 AM <br />1:14 PM</p></div>-->
										<div class="col-sm-6"><p>Airport Express to <i>Lyon City Center</i><br />No details yet.</p></div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-1 col-sm-offset-1"><p class="icon">👩‍🌾</p></div>
									<div class="col-sm-9"><p>Drop off stuff at the airbnb and meet up with grandma.</p></div>
								</div>
								<div class="row">
									<div class="col-sm-1 col-sm-offset-1"><p class="icon">🎠</p></div>
									<div class="col-sm-9"><p><a href="https://docs.google.com/document/d/16LIO3pElPAxJFJG2iSEOPhIPGIlKbx9x6uZY4gcVlGkc/edit?ts=5a5f8f39" target="_blank">Jillian Itinerary - Walking Tour</a><br />Tour 1 - Fouviere hill, basilica, walk to l' epicerie.</p></div>
								</div>
								<div class="bs-callout bs-callout-danger bs-callout-overnight">
									<div class="row">
										<div class="col-sm-1"><p class="icon">🏠</p></div>
										<div class="col-sm-6"><p><a href="https://www.airbnb.com/reservation/itinerary?code=HM4T" target="_blank">Airbnb - HM123</a><br />1 Rue Saint-Jean, Lyon, 69005</p></div>
											<div class="col-sm-4"><p>Sara<br /><a href="tel:+555555">+33 5 55 55 89 75</a></p></div>
									</div>
								</div>
							</div>
							<div class="panel panel-info panel-overnight">
								<h2 class="itinerary-header">Friday<small>June 15</small></h2>
								<div class="row">
									<div class="col-sm-1 col-sm-offset-1"><p class="icon">🎠</p></div>
									<div class="col-sm-9"><p><a href="https://docs.google.com/document/d/16LIO3pElPAxJFJG2iSEOPhIPGIlKbx9x6uZY4gcVlGkc/edit?ts=5a5f8f39" target="_blank">Jillian Itinerary - Walking Tour</a><br />Tour 1 - Fouviere hill, basilica, walk to l' epicerie.</p></div>
								</div>
								<div class="bs-callout bs-callout-danger bs-callout-overnight">
									<div class="row">
										<div class="col-sm-1"><p class="icon">🏠</p></div>
										<div class="col-sm-6"><p><a href="https://www.airbnb.com/reservation/itinerary?code=MHER" target="_blank">Airbnb - HM123</a><br />1 Rue Saint-Jean, Lyon, 69005</p></div>
										<div class="col-sm-4"><p>Sara<br /><a href="tel:+555555">+33 5 55 55 89 75</a></p></div>
									</div>
								</div>
							</div>
							<div class="panel panel-info panel-overnight">
								<h2 class="itinerary-header">Saturday<small>June 16</small></h2>
								<div class="row">
									<div class="col-sm-1 col-sm-offset-1"><p class="icon">🎠</p></div>
									<div class="col-sm-9"><p><a href="https://docs.google.com/document/d/16LIO3pElPAxJFJG2iSEOPhIPGIlKbx9x6uZY4gcVlGkc/edit?ts=5a5f8f39" target="_blank">Jillian Itinerary - Walking Tour</a><br />Tour 1 - Fouviere hill, basilica, walk to l' epicerie.</p></div>
								</div>
							</div>
						</div>
						

<!--
	<div class="row">
 <h3>Bootstrap Timeline Example <button class="btn btn-primary" id="add">Click To add event</button></h3>
    <ul class="timeline">
        <li>
          <div class="timeline-badge info"><i class="glyphicon glyphicon-hand-left"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Bootstrap 3 released</h4>
              <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> August 2013</small></p>
            </div>
            <div class="timeline-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
              Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero. Aenean sit amet felis 
              dolor, in sagittis nisi. Sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
              Aliquam in felis sit amet augue.</p>
            </div>
          </div>
        </li>
        <li class="timeline-inverted">
          <div class="timeline-badge warning"><i class="glyphicon glyphicon-chevron-right"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Bootstrap 2</h4>
            </div>
            <div class="timeline-body">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pharetra varius quam sit amet vulputate. 
              Quisque mauris augue, molestie tincidunt condimentum vitae, gravida a libero. Aenean sit amet felis 
              dolor, in sagittis nisi. Sed ac orci quis tortor imperdiet venenatis. Duis elementum auctor accumsan. 
              Aliquam in felis sit amet augue.</p>
            </div>
          </div>
        </li>
        <li>
          <div class="timeline-badge danger"><i class="glyphicon glyphicon-eye-open"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Left Event</h4>
              <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> 3 years ago</small></p>
            </div>
            <div class="timeline-body">
              <p>Add more progress events and milestones to the left or right side of the timeline. Each event can be tagged with a date and given a beautiful icon to symbolize it's spectacular meaning.</p>
            </div>
          </div>
        </li>
        <li class="timeline-inverted">
          <div class="timeline-badge default"><i class="glyphicon glyphicon-home"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Right Event</h4>
            </div>
            <div class="timeline-body">
              <p>Add more progress events and milestones to the left or right side of the timeline. Each event can be tagged with a date and given a beautiful icon to symbolize it's spectacular meaning.</p>
            </div>
          </div>
        </li>
        <li>
          <div class="timeline-badge default"><i class="glyphicon glyphicon-home"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Left Event</h4>
            </div>
            <div class="timeline-body">
              <p>Add more progress events and milestones to the left or right side of the timeline. Each event can be tagged with a date and given a beautiful icon to symbolize it's spectacular meaning.</p>
            
              <hr>
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li><a href="#">Separated link</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>
        <li>
           <div class="timeline-badge default"><i class="glyphicon glyphicon-arrow-left"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Left Event</h4>
            </div>
            <div class="timeline-body">
              <p>Add more progress events and milestones to the left or right side of the timeline. Each event can be tagged with a date and given a beautiful icon to symbolize it's spectacular meaning.</p>
            </div>
          </div>
        </li>
        <li class="timeline-inverted">
          <div class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
          <div class="timeline-panel">
            <div class="timeline-heading">
              <h4 class="timeline-title">Oldest event</h4>
            </div>
            <div class="timeline-body">
              <p>Add more progress events and milestones to the left or right side of the timeline. Each event can be tagged with a date and given a beautiful icon to symbolize it's spectacular meaning.</p>
            </div>
          </div>
        </li>
    </ul> editor tabs
	</div>						
-->
						
						
					</div><!-- .trip-itinerary -->
				</div>
			</div>
		</div>
		
		
		
	</div>
	<!-- /Jumbotron -->
	<!-- Panels -->
	<div class="container top-nospace">
		<div class="row">
			<div class="col-lg-12">
				
			</div>
		</div>
		<div class="row">
			<div class="col-lg-7">
				<div class="top-space">
					<p><p>To see only those thinkers who inform the complexity sciences, see:</p><ul class="breadcrumb"><li class="active"><a href="/taxonomy/complexity-theory-key-sources" data-toggle="tooltip" data-title="This page highlights  key thinkers who helped pioneer ideas that continue to inform CAS sciences. ">Complexity Theory Key Sources</a></li></ul><p><span >To see only those thinkers who come from the spatial disciplines but who have informed the discussion on complexity, see:</span></p><ul class="breadcrumb" ><li class="active"><a href="/taxonomy/urban-theory-key-thinkers" data-toggle="tooltip" data-title="This page highlights individuals  who come primarily from the spatial disciplines but whose way of approaching urban theory relates to CAS (either directly or indirectly)">Urban Theory Key Sources</a></li></ul><p><br></p><p><br></p><p>ado, turkish filter, cultivar plunger pot eu coffee grounds whipped wings. Sugar, mazagran, grounds, café au lait sweet viennese aroma foam, extraction, aroma galão whipped, con panna latte as decaffeinated mazagran. Coffee, roast crema that con panna pumpkin spice latte skinny aged sit mocha, crema, coffee shop that, sit carajillo single origin black steamed filter. Doppio breve medium, sit kopi-luwak, irish sweet caffeine, kopi-luwak, filter, americano aromatic bar roast decaffeinated. Spoon est plunger pot, java, foam mocha coffee acerbic extra, trifecta skinny fair trade cappuccino extraction blue mountain variety chicory variety at single origin mazagran.</p></p>
				</div>
								<!-- Footer parentchild -->
						<hr>
						<div class="row">
							<div class="col-md-6">
								<h4><strong>Continue Exploring</strong></h4>
								<p>Explore Key Thinkers further in the topics and collections below.</p>
																																	<h4><a class="t_list_148" href="/definition/zipf">Zipf</a></h4>
									<!--<p>relates to {{power-laws}} <a href="/definition/zipf">Learn More about Zipf &rarr;</a></p>-->
																	<h4><a class="t_list_132" href="/definition/stuart-kauffman">Stuart Kauffman</a></h4>
									<!--<p>jkdlsj <a href="/definition/stuart-kauffman">Learn More about Stuart Kauffman &rarr;</a></p>-->
																	<h4><a class="t_list_144" href="/definition/steven-strogatz">Steven Strogatz</a></h4>
									<!--<p>Relates to understanding of networks <a href="/definition/steven-strogatz">Learn More about Steven Strogatz &rarr;</a></p>-->
																	<h4><a class="t_list_175" href="/definition/claude-shannon">Claude Shannon</a></h4>
									<!--<p>x <a href="/definition/claude-shannon">Learn More about Claude Shannon &rarr;</a></p>-->
																	<h4><a class="t_list_158" href="/definition/chris-langton">Chris Langton</a></h4>
									<!--<p>x <a href="/definition/chris-langton">Learn More about Chris Langton &rarr;</a></p>-->
																	<h4><a class="t_list_133" href="/definition/charles-waldheim">Charles Waldheim</a></h4>
									<!--<p>important LU guy <a href="/definition/charles-waldheim">Learn More about Charles Waldheim &rarr;</a></p>-->
																	<h4><a class="t_list_149" href="/definition/charles-darwin">Charles Darwin</a></h4>
									<!--<p>Evolution <a href="/definition/charles-darwin">Learn More about Charles Darwin &rarr;</a></p>-->
																	<h4><a class="t_list_134" href="/definition/holling">C. S. Holling</a></h4>
									<!--<p>Holling relates concepts from CAS theory with notions of Resiliency. <a href="/definition/holling">Learn More about C. S. Holling &rarr;</a></p>-->
																	<h4><a class="t_list_168" href="/definition/bernhard-riemann">Bernhard Riemann</a></h4>
									<!--<p>x <a href="/definition/bernhard-riemann">Learn More about Bernhard Riemann &rarr;</a></p>-->
																	<h4><a class="t_list_171" href="/definition/benoit-mandelbrot">Benoit Mandelbrot</a></h4>
									<!--<p>x <a href="/definition/benoit-mandelbrot">Learn More about Benoit Mandelbrot &rarr;</a></p>-->
																	<h4><a class="t_list_172" href="/definition/albert-laszlo-barabasi">Albert Laszlo Barabasi</a></h4>
									<!--<p>x <a href="/definition/albert-laszlo-barabasi">Learn More about Albert Laszlo Barabasi &rarr;</a></p>-->
																	<h4><a class="t_list_3" href="/taxonomy/key-thinkers">Key Thinkers</a></h4>
									<!--<p>These are the people who established the fundamentals of complexity theory or are voices promoting the theory and understanding of Complex Adaptive Systems.
See also {{Key-Thinker-Diagrams}} <a href="/taxonomy/key-thinkers">Learn More about Key Thinkers &rarr;</a></p>-->
								 
															</div>
							<div class="col-md-6">
								<h4><strong>Parents</strong></h4>
								<p>Key Thinkers is part of the following collections.</p>
																																									<h4><a class="t_list_3" href="/taxonomy/key-thinkers">Key Thinkers</a></h4>
									<!--<p>These are the people who established the fundamentals of complexity theory or are voices promoting the theory and understanding of Complex Adaptive Systems.
See also {{Key-Thinker-Diagrams}} <a href="/taxonomy/key-thinkers">Learn More about Key Thinkers &rarr;</a></p>-->
								 
																							</div>
						</div>
				<!-- /Footer List -->
																				<div class="page-header">
					<ul class="breadcrumb">
						<li class="active">Key Thinkers {{key-thinkers}} was updated September 6th, 2017.</li>
					</ul>			
				</div>
			</div>


			<div class="col-lg-5">
				<h3>Feed </h3>
				<p>This is the feed, a series of things related to Key Thinkers. <a data-toggle="modal" data-target="#createlink">Add a link to the feed &rarr;</a></p>
				 
						<blockquote>Nothing in the feed...yet.<br /><button class="btn btn-success" data-toggle="modal" data-target="#createlink">Create a Link</button></blockquote>
							</div>

		</div>
	</div>
