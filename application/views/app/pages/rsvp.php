<style>
.build-wrapper {
	display: none !important;
}
.rsvpframe {
	color: black;
}
body {
	background: #8f7f57;
}
.fancysub {
	font-family: ltc-caslon-pro, serif;
	font-style: normal;
	letter-spacing: -.05em;
	line-height: 1em;
	text-transform: none;
}
.fancyp {
	font-family: ltc-caslon-pro, serif;
	font-style: normal;
	letter-spacing: -.05em;
	line-height: 1em;
	text-transform: none;
	font-size: 1.5em;
	margin: .9em 0;
}

.fancytitle {
	font-family: lust-display,sans-serif;
	font-size: 4em;
	line-height: .9em;
	color: #000;
	font-style: italic;
	letter-spacing: -.02em;
	padding-top: 0;
	text-transform: none;
}
.fancytext {
	text-transform: none;
}
.radiosession * {
  box-sizing: border-box;
}

.radio-tile-group {
  display: flex;
  flex-wrap: wrap;
  justify-content: left;
}
.radio-tile-group .input-container {
  position: relative;
  height: 7rem;
  width: 13rem;
  margin: 0.4rem 1rem;
}
.radio-tile-group .input-container .radio-button {
  opacity: 0;
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  margin: 0;
  cursor: pointer;
}
.radio-tile-group .input-container .radio-tile {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  border: 2px solid #f2dc73;
  border-radius: 5px;
  padding: 1rem;
  transition: transform 300ms ease;
}
.radio-tile-group .input-container .radio-tile.green {
  border: 2px solid #8bc34a;
}
.radio-tile-group .input-container .radio-tile.red {
  border: 2px solid #f44336;
}
.radio-tile-group .input-container .radio-tile-label {
  text-align: center;
  font-size: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile {
  background-color: #f2dc73;
  border: 2px solid #f2dc73;
  color: black;
  transform: scale(1.1, 1.1);
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile.green {
  background-color: #8bc34a;
  border: 2px solid #8bc34a;
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile.red {
  background-color: #f44336;
  border: 2px solid #f44336;
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile.green .radio-tile-label {
  color: white;
  background-color: #8bc34a;
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile.red .radio-tile-label {
  color: white;
  background-color: #f44336;
}
.radio-tile-group .input-container .radio-button:checked + .radio-tile .radio-tile-label {
  color: black;
  background-color: #f2dc73;
}
.rsvpframe .dropdown-menu {
	display: block;
	margin-top: -52px;
}
.rsvpframe .dropdown-menu .inner {
	max-height: 28px;
	overflow-y: auto;
}
a:link, a:visited {
	color: #000;
	text-decoration: underline;
	
}
a:hover {
	color: #000;
	text-decoration: none;
}
a:active {
	color: #000;
	text-decoration: underline;
	
}
hr {
	border: 1px dotted #000;
}

.btn-primary {
	color: #000000;
	background-color: #f2dc73;
	border-color: transparent;
}
.btn-primary:focus,
.btn-primary.focus {
  color: #000000;
  background-color: #bbaa5b;
  border-color: rgba(0, 0, 0, 0);
}
.btn-primary:hover {
  color: #000000;
  background-color: #d0bd65;
  border-color: rgba(0, 0, 0, 0);
}
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  color: #000000;
  background-color: #968949;
  border-color: rgba(0, 0, 0, 0);
}
.btn-primary:active:hover,
.btn-primary.active:hover,
.open > .dropdown-toggle.btn-primary:hover,
.btn-primary:active:focus,
.btn-primary.active:focus,
.open > .dropdown-toggle.btn-primary:focus,
.btn-primary:active.focus,
.btn-primary.active.focus,
.open > .dropdown-toggle.btn-primary.focus {
  color: #000000;
  background-color: #9c8e4a;
  border-color: rgba(0, 0, 0, 0);
}
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  background-image: none;
}
h1,h2 {
	font-weight: normal;
}

	</style>
	<script src="/includes/js/iframeheightutility.min.js" type="text/javascript"></script>
	<!-- Panels -->
	<div class="rsvpframe">
		
		<div class="row">
			<div class="col-lg-8">
				<?php $page = $this->input->get('step'); if ($page === null || $this->input->get('key') == '0') { ?>
				<h1 class="fancytitle">RSVP and Meal Choices</h1>
				<h2 class="fancysub">Need to make a change to your RSVP for the wedding? <br /><br />Instead of making a change online, please call Sean or Jillian to give us a heads up as we have already started to set the table and prepare meal lists for the caterer. <br /><br />Thank you!</h2>
				<hr />
				<h2 class="fancyp">Given space and budget restrictions, we can only accommodate those on your invite. Feel free to reach out to Jillian or Sean (<a href="mailto:jilliangetswitty@gmail.com">email</a>) with questions or if we misspelled your name (oops!).</h2>
				<h2 class="fancyp">We hope you will understand our decision to make the wedding children-free, and take the opportunity to let your hair down and celebrate in style! There are some <a href="https://www.care.com/weekend-child-care/leavenworth-wa" target="_blank">child care options and sitters in the Leavenworth/Wenatchee area</a> if needed.</h2>
				<h2 class="fancyp">Please reach out to us (<a href="mailto:jilliangetswitty@gmail.com">via email</a>) if you have food allergies and other dietary restrictions and we will try to do what we can to accommodate these however we may be limited with our options.</h2>
				<?php if ($this->input->get('show') == 'everyone') { ?>
				<hr />
				<h3 class="fancytext">Let's start with your name:</h3>
				<div style="margin-left: 0px;">
					<script>
					    $(function(){
					      // bind change event to select
					      $('#rsvpsearch').on('change', function () {
					          var url = $(this).val(); // get selected value
					          if (url) { // require a URL
					              window.location = url; // redirect
					          }
					          return false;
					      });
					    });
					</script>

					<select name="search" id="rsvpsearch" class="selectpicker btn-sm" data-width="100%" data-live-search="true" data-size="15" onchange="">
						<option value="https://seanwittmeyer.com/rsvp" selected>Search for your first or last name and push enter...</option>
						<option value="https://seanwittmeyer.com/rsvp"> </option>
						<option value="https://seanwittmeyer.com/rsvp"> </option>
					<?php // List definitions
						$list = $this->shared->get_data2('rsvp'); 
						foreach ($list as $a) {  
							foreach (array(1,2,3,4) as $n) {
								$v = "name$n";
								if ($a[$v] !== '') echo "<option value=\"https://seanwittmeyer.com/rsvp?step=status&key={$a['id']}&name={$a[$v]}\">{$a[$v]}</option>\n";
							}
						} ?> 
					</select>
				</div>
				<?php }} elseif ($page == 'status') { 
					$reservation = $this->shared->get_data2('rsvp',$this->input->get('key'));
					$name = urldecode($this->input->get('name'));
					if (is_array($reservation)) {
						$count = 0; $user = 'name1';
						for ($i=1; $i<=4; $i++) {
							if ($reservation['name'.$i] !== "") $count++;
							if ($name == $reservation['name'.$i]) $user = "name$i";
						}
					}
				?>
				<h1 class="fancytitle">Hey <?php echo strtok($name, ' '); ?>!</h1>
				<h2 class="fancysub">We found your invite<?php if ($count > 2) { ?> for <strong><?php echo $count; ?> people<?php } elseif ($count == 2) { ?> for you and <?php echo ($user == 'name2') ? strtok($reservation['name1'], ' '): strtok($reservation['name2'], ' '); } ?>! Let's set whether or not you'd be able to join us on January 18th. <a href="/rsvp" style="font-size: .5em;"><br />Not <?php echo strtok($name, ' '); ?>? Head back &rarr;</a></h2>

				<?php echo form_open("rsvp?step=food");?>
				<input type="hidden" name="step" value="status" />
				<input type="hidden" name="payload[checkedin]" value="1" />
				<input type="hidden" name="payload[timestamp]" value="<?php echo time(); ?>" />
				<input type="hidden" name="payload[id]" value="<?php echo $reservation["id"]; ?>" />
				<input type="hidden" name="name" value="<?php echo $name; ?>" />
				<?php for ($i=1; $i<=$count; $i++) { ?>
				<hr />
				<div class="row">
					<div class="col-xs-6">
						<h3 class="fancytext"><?php echo $reservation["name$i"];?></h3>
					</div>
					<div class="col-xs-6">
						<div class="radiosession">
						  <div class="radio-tile-group">
						
						    <div class="input-container">
						      <input id="walk" class="radio-button" type="radio" <?php if ($reservation["status$i"] == 1) echo 'checked'; ?> required name="payload[status<?php echo $i; ?>]" value="1" />
						      <div class="radio-tile green">
						        <div class="icon walk-icon">
						          <i style="font-size: 2em;" class="fa fa-check" title="Yes"></i>
						        </div>
						        <label for="walk" class="radio-tile-label"><?php echo strtok($reservation["name$i"], ' '); ?> will be there</label>
						      </div>
						    </div>
						
						    <div class="input-container">
						      <input id="bike" class="radio-button" type="radio" <?php if ($reservation["status$i"] == 2) echo 'checked'; ?> required name="payload[status<?php echo $i; ?>]" value="2" />
						      <div class="radio-tile red">
						        <div class="icon bike-icon">
						          <i style="font-size: 2em;" class="fa fa-times" title="No"></i>
						        </div>
						        <label for="bike" class="radio-tile-label"><?php echo strtok($reservation["name$i"], ' '); ?> can not attend</label>
						      </div>
						    </div>
						  </div>
						</div>
					</div>
				</div>
				<?php } ?>
				<h2 class="fancyp"><?php echo ($count >= 2) ? "$count seats have": "$count seat has" ?> been reserved in your honor. Given space and budget restrictions, we can not extend the invitation to others.  Feel free to reach out to Jillian or Sean (<a href="mailto:jilliangetswitty@gmail.com">email</a>) with questions or if we misspelled your name (oops!).</h2>
				<h2 class="fancyp">We hope you will understand our decision to make the wedding children-free, and take the opportunity to let your hair down and celebrate in style! There are some <a href="https://www.care.com/weekend-child-care/leavenworth-wa" target="_blank">child care options and sitters in the Leavenworth/Wenatchee area</a> if needed.</h2>
				<input type="submit" class="btn btn-primary tt" id="submit" value="Done, on to meal choices &rarr;" />
				</form>


				<?php } elseif ($page == 'food') { 
					if ($this->input->post('payload[id]')) {
					$choices = $this->input->post('payload');
					unset($choices['id']);
					
					$this->db->where('id', $this->input->post('payload[id]'));
					$this->db->update('build_rsvp', $choices);

					$reservation = $this->shared->get_data2('rsvp',$this->input->post('payload[id]'));
					$name = $this->input->post('name');
					if (is_array($reservation)) {
						$count = 0; 
						$attendees = 0;
						$user = 'name1';
						for ($i=1; $i<=4; $i++) {
							if (!empty($reservation['name'.$i])) $count++;
							if ($reservation['status'.$i] == 1) $attendees++;
							if ($name == $reservation['name'.$i]) $user = "name$i";
						}
						// did anyone mark yes?
						
						if ($attendees > 0) {
					?>
							<h1 class="fancytitle">Alright <?php echo strtok($name, ' '); ?>, food!</h1>
							<h2 class="fancysub">Let us know what your meal preferences are for the reception.</h2>
			
							<?php echo form_open("rsvp?step=done");?>
							<input type="hidden" name="step" value="food" />
							<input type="hidden" name="payload[id]" value="<?php echo $reservation["id"]; ?>" />
							<input type="hidden" name="payload[timestamp]" value="<?php echo time(); ?>" />
							<input type="hidden" name="name" value="<?php echo $name; ?>" />
							<?php for ($i=1; $i<=$count; $i++) { if ($reservation["status$i"] == 1) { ?>
							<hr />
							<div class="row">
								<div class="col-xs-6">
									<h3 class="fancytext"><?php echo $reservation["name$i"];?></h3>
								</div>
								<div class="col-xs-6">
									<div class="radiosession">
									  <div class="radio-tile-group">
									
									    <div class="input-container">
									      <input id="walk" class="radio-button" type="radio" <?php if ($reservation["food$i"] == 1) echo 'checked'; ?> required name="payload[food<?php echo $i; ?>]" value="1" />
									      <div class="radio-tile">
									        <div class="icon walk-icon">
									          <i style="font-size: 1.8em;" class="fa fa-cutlery" title="salmon"></i>
									        </div>
									        <label for="walk" class="radio-tile-label"><?php echo strtok($reservation["name$i"], ' '); ?> wants salmon</label>
									      </div>
									    </div>
									
									    <div class="input-container">
									      <input id="bike" class="radio-button" type="radio" <?php if ($reservation["food$i"] == 2) echo 'checked'; ?> required name="payload[food<?php echo $i; ?>]" value="2" />
									      <div class="radio-tile">
									        <div class="icon bike-icon">
									          <i style="font-size: 1.8em;" class="fa fa-cutlery" title="Steak"></i>
									        </div>
									        <label for="bike" class="radio-tile-label"><?php echo strtok($reservation["name$i"], ' '); ?> wants steak</label>
									      </div>
									    </div>
									  </div>
									</div>
								</div>
							</div>
							<?php } else { ?>
							<hr />
							<h2 class="fancyp">We saved <?php echo strtok($reservation["name$i"], ' '); ?> as unable to join. Make a mistake? You can <a href="/rsvp?step=status&key=<?php echo $reservation["id"]; ?>&name=<?php echo $name; ?>">head back to make changes</a>. </h2>
							<?php }} ?>
					<hr />
					<h2 class="fancyp">Please reach out to us (<a href="mailto:jilliangetswitty@gmail.com">via email</a>) if you have food allergies and other dietary restrictions and we will try to do what we can to accommodate these however we may be limited with our options.</h2>
					<input type="submit" class="btn btn-primary tt" id="submit" value="Yummm, anything else? &rarr;" />
					</form>

				<?php // else all no's
					} else { ?>
						
							<h1 class="fancytitle">Well shoot...</h1>
							<h2 class="fancyp">We saved you as unable to join. Make a mistake? You can <a href="/rsvp?step=status&key=<?php echo $reservation["id"]; ?>&name=<?php echo $name; ?>">head back to make changes</a>. </h2>
							<h2 class="fancyp">We appreciate you taking to time to fill out the RSVP and hope that we can all spend some time together soon to share stories about how the big day and the honeymoon go (awesome as anticipated). <br /><br />Best, Sean &amp; Jillian</h2>
				<?php }}
					
				}} elseif ($page == 'done') { 
					if ($this->input->post('payload[id]')) {
					$choices = $this->input->post('payload');
					unset($choices['id']);
					
					$this->db->where('id', $this->input->post('payload[id]'));
					$this->db->update('build_rsvp', $choices);

					$reservation = $this->shared->get_data2('rsvp',$this->input->post('payload[id]'));
					$name = $this->input->post('name');
					if (is_array($reservation)) {
						$count = 0; 
						$attendees = 0;
						$user = 'name1';
						for ($i=1; $i<=4; $i++) {
							if ($reservation['name'.$i] !== "") $count++;
							if ($reservation['status'.$i] == 1) $attendees++;
							if ($name == $reservation['name'.$i]) $user = "name$i";
						}
					?>
							<h1 class="fancytitle">That's it!</h1>
							<h2 class="fancysub">Thank you for taking the time to RSVP, we will see you in Leavenworth on January, 18th!</h2>
							<h2 class="fancyp">We put a page with some information about the big day to help with your planning for the weekend. <a href="http://jilliangetswitty.us/leavenworth" target="_parent">Detail &amp; FAQ &rarr;</a></h2>



				<?php }}} else { ?>
				<h1 class="fancytitle">I don't know what happened.</h1>
				<h2 class="fancysub">I mean, really, what kind of wedding website is this? ;) Let's send you on your way to the start of this and try again.</h2>
				<p><a class="btn btn-primary btn-md" href="/rsvp">RSVP for the Wedding</a></p>
				<p>One note, if you get this error multiple times, send Sean a message via text or <a href="mailto:jilliangetswitty@gmail.com">email</a> and we will get you all set up.</p>
				<?php } 
					if ($this->input->get('show')=='everyone') {
						$meal = array('-','salmon','steak');
				?>
			</div>
		</div>
		<div class="row" style="margin: 40px;">
			<div class="col-lg-8">
				<table class="table" style="margin-top: 50px;">
					<caption>List of everyone in the RSVP Database</caption>
					<thead>
						<tr>
						<th>Status</th>
						<th>Seats</th>
						<th>Name (meal choice)</th>
						<th>Name (meal choice)</th>
						<th>Name (meal choice)</th>
						<th>Name (meal choice)</th>
						</tr>
					</thead>
					<tbody>
    				<?php 
						$totalcount = 0; 
						$checkins = 0; 
						$attendees = 0;
						$declines = 0;
						$unknowns = 0;
						$rsvps = $this->shared->get_data2('rsvp');
						$countreservations = count($rsvps);
						foreach ($rsvps as $attendee) { 
						$count = 0; 
						if ($attendee['checkedin'] == 1) $checkins++;
						for ($i=1; $i<=4; $i++) {
							if ($attendee['name'.$i] !== "") $count++;
							if ($attendee['status'.$i] == 1) $attendees++; else if ($attendee['status'.$i] == 2) $declines++; elseif ($attendee['status'.$i] == 1) $unknowns++;
						}
    				?>
						<tr>
							<th scope="row"><?php echo ($attendee['checkedin'] == 1) ? '<i style="color: green;" class="fa fa-check"></i>':'<i style="color: red;" class="fa fa-times"></i>'; ?></th>
							<td><?php echo $count; ?></td>
							<td><?php if ($attendee['status1'] == 1) echo "<strong>{$attendee['name1']}</strong> ({$meal[$attendee['food1']]})"; elseif ($attendee['status1'] == 2) echo "<span style='color:#BBB;'>{$attendee['name1']}</span>"; else echo $attendee['name1'];  ?></td>
							<td><?php if ($attendee['status2'] == 1) echo "<strong>{$attendee['name2']}</strong> ({$meal[$attendee['food2']]})"; elseif ($attendee['status2'] == 2) echo "<span style='color:#BBB;'>{$attendee['name2']}</span>"; else echo $attendee['name2'];  ?></td>
							<td><?php if ($attendee['status3'] == 1) echo "<strong>{$attendee['name3']}</strong> ({$meal[$attendee['food3']]})"; elseif ($attendee['status3'] == 2) echo "<span style='color:#BBB;'>{$attendee['name3']}</span>"; else echo $attendee['name3'];  ?></td>
							<td><?php if ($attendee['status4'] == 1) echo "<strong>{$attendee['name4']}</strong> ({$meal[$attendee['food4']]})"; elseif ($attendee['status4'] == 2) echo "<span style='color:#BBB;'>{$attendee['name4']}</span>"; else echo $attendee['name4'];  ?></td>
						</tr>
					<?php $totalcount = $totalcount+$count; } ?>
					</tbody>
				</table>
				<h2>Caterer Table</h2>
				<table class="table" style="margin-top: 50px;">
					<caption>List of everyone in the RSVP Database</caption>
					<thead>
						<tr>
						<th>Name</th>
						<th>Table</th>
						<th>Meal Choice</th>
						<th>Comment</th>
						</tr>
					</thead>
					<tbody>
    				<?php 
						$totalcount = 0; 
						$checkins = 0; 
						$attendees = 0;
						$declines = 0;
						$unknowns = 0;
						$rsvps = $this->shared->get_data2('rsvp');
						foreach ($rsvps as $attendee) { 
						$count = 0; 
						if ($attendee['checkedin'] == 1) $checkins++;
						for ($i=1; $i<=4; $i++) {
							if ($attendee['name'.$i] !== "") $count++;
							if ($attendee['status'.$i] == 1) $attendees++; else if ($attendee['status'.$i] == 2) $declines++; elseif ($attendee['status'.$i] == 1) $unknowns++;
						}
						if ($attendee['status1'] == 1) echo "<tr><th>{$attendee['name1']}</th><td>Table</td><td>{$meal[$attendee['food1']]}</td><td>Comment</td></tr>";
						if ($attendee['status2'] == 1) echo "<tr><th>{$attendee['name2']}</th><td>Table</td><td>{$meal[$attendee['food2']]}</td><td>Comment</td></tr>";
						if ($attendee['status3'] == 1) echo "<tr><th>{$attendee['name3']}</th><td>Table</td><td>{$meal[$attendee['food3']]}</td><td>Comment</td></tr>";
						if ($attendee['status4'] == 1) echo "<tr><th>{$attendee['name4']}</th><td>Table</td><td>{$meal[$attendee['food4']]}</td><td>Comment</td></tr>";
						$totalcount = $totalcount+$count; } ?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-4">
				<h2 class="fancyp">Invites: <?php echo $checkins.'/'.$countreservations; ?></h2>
				<h2 class="fancyp">Accepted Count: <?php echo $attendees.' ('.floor($attendees/$totalcount*100).'%)'; ?></h2>
				<h2 class="fancyp">Declines Count: <?php echo $declines.' ('.floor($declines/$totalcount*100).'%)'; ?></h2>
			</div>
				<?php } ?>
		</div>
	</div>
	
