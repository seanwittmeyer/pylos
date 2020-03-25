<!-- Content Area -->
		<div class="row">
			<div class="col-lg-12 examples">
				<!--<div class="header"></div>-->
				<div>
					<!--<h2 class="example-description"></h2>-->
					<div class="meta"></div>
					<div class="example-tags"></div>
					<!--<form id="example-download">
						<p><button id="downloadButton" type="button">Download</button></p>
					</form>
					<!-- <select id="versions" style = "float:right"> </select> -->
					
				</div>
				
				<div class="example-readme"></div>
				<h3>Dependencies</h3>
				<div class="example-dependencies" title="File Dependencies"></div>
				<h3>Component List</h3>
				<div class="example-components" title="Component List"></div>

			</div>
		</div>
		
<!-- End Content Area -->



					</div><!-- this closes the main content region -->
					<div class="col-sm-3">
						<div class="headline-parent"><h3 class="text-left headline">Tools</h3></div>
						<h5>Meta</h5>
						<p><span id="last_updated"></span></p>
						<p><span id="load_time"></span></p>
						<div class="meta"></div>
						<div id="hydratags"></div>
						<form id="hydradownload">
							<p><button id="downloadButton" class="btn btn-primary" type="button">Download</button></p>
						</form>

						<h5>Search</h5>
						<div>
							<input type="text" id="filtering" placeholder="username, component name,&hellip;" title="Filter the results by username, component name or keywords. Separate words by ,">
							<select>
								<option value="created_at">Newest</option>
								<option value="modified_at">Last updated</option>
								<option value="id">A-Z</option>
								<!--
								<option value="downloads">most downloaded</option>
								<option value="most_visited">most visited</option>
								<option value="best_rated">best rated</option>
								-->
							</select>
						</div>
						<h5><a>Sign in</a></h5>
						<h5><a>Edit this category</a></h5>
					</div>
				</div>
			</div>
			<!-- /main -->
		</div>
	</div>
	<!-- /Top -->
	<!-- Panels -->


<script data-cfasync="false" type="text/javascript">
var example,
	searchwkeywordbasepath = document.location.origin + "/pylos/hydra?keywords=",
	scale = 1, // current zoom
	offset = "0,0",
	slide = 0; // current offset from the center default is 0,0


function parseUri(){
	// This function parses uri to find owner and project name/description
	// and creates the base json object to generate the page

	var inputParameters = {
		owner : "",
		id : "",
		fork: "",
		scale: 1, 
		offset: "0,0",
		slide: 0
	}

	// parse Uri
	var parse = window.location.search.replace("?","").split("&")

	if (parse[0]=="") {
		alert("Can't find the file!");
		return;
	}

	parse.forEach(function(d){
		keyValue = d.split("=");
		inputParameters[keyValue[0].toLowerCase()] = decodeURIComponent(keyValue[1]);
	})

	var owner = inputParameters.owner,
		fork = inputParameters.fork,
		id = inputParameters.id;
	
	scale = inputParameters.scale;
	offset = inputParameters.offset.split(",");
	slide = inputParameters.slide;

	// read the json file
	var githubBase = "https://raw.githubusercontent.com/" + owner + "/" + fork + "/master/" + id;
	var jsonFile = githubBase + "/input.json";

	var formatDate = d3.time.format("%B %-d, %Y"),
			dates = [];

  d3.json(jsonFile, function(error, data){

    	if (d3.keys(data).indexOf("dependencies") == -1) {
    		// this example if generated by an older version
    		// modify it for the newer version
    		data.images = [{}, {}];
    		data.images[0][data.ghimg] = "Grasshopper Definition";
    		data.images[1][data.rhinoimg] = "Rhino Viewport Screenshot";

    		data.dependencies = ["This example is generated by an old version of Hydra/Pylos! Please re-generate the example file."];
    	}

		  var inputFileHistory = "https://api.github.com/repos/" + owner + "/" + fork + "/commits?path=" + id + "/input.json";

			d3.json(inputFileHistory, function(error, inputHistoryData){
			  // collect all dates
			  for (d = 0; d < inputHistoryData.length; d++){
			    var commit = inputHistoryData[d];
			    // adding milliseconds to date > http://stackoverflow.com/questions/6683872/why-does-my-date-object-in-google-apps-script-return-nan
			    var dt = Date.parse(commit.commit.committer.date.replace("Z", ".000Z"));
			    var dtt = new Date(dt);
			    dates.push(dtt);
			  }

		  	// sort list of dates
		  	dates.sort();

	    	// use the id to get the following github information
			example = {
				"githubBase": githubBase,
				"owner": owner,
				"description": id,
				"fork": fork,
				"fork_path": "http://www.github.com/" + owner + "/" + fork + "/tree/master/" + id,
				"images": data.images,
				"videos": data.videos,
				"download_path": githubBase + "/" + data.file,
				"readme_path": githubBase + "/README.md",
				"components": data.components,
				"dependencies": data.dependencies,
				"tags": data.tags,
				"versions": data.versions,
				"created_at": formatDate(dates[0])
			};

			generateContent(example);
			});
		});

};

parseUri();

function generateContent(){

	// script for title
	d3.select("title").text(example.description + 'Pylos');
	d3.select("#hydratitle").text(example.description.replace(/_/g, " "));
	d3.select("#hydradate").text(example.description);

	// script for header
	//var header = d3.select(".header").append("span")

	// script for meta data
	var metadata = d3.select(".meta").append("span")

	metadata.append("a")
		.attr("href", searchwkeywordbasepath + example.owner)
	  	.text(example.owner);

	metadata.append("span").text("'s example ");

	metadata.append("a")
	  .attr("title","View example on Github")
	  .attr("href", example.fork_path)
	  .text(example.description + " ");
	metadata.append("span")
	  .attr("class","date")
	  .text(example.created_at);
	/*
	header.append("a")
	  .attr("href", document.location.origin + "/pylos/hydra")
	  .style("float","right")
	  .text("Return to Main Page")
	 */

	// script for description
	//d3.select(".example-description").text(example.description.replace(/_/g, " "));


	// bind the download submit event to the form
	d3.select("button#downloadButton")
		.on("click", function(){
		//download the zip file from github
		window.location = example.download_path;
		});

	// change toggle view between images
	// add lable for each image in image list
	d3.select("#slideshow")
		.selectAll("lable")
		.data(example.images)
		.enter()
			.append("label")
			.append("input")
				.attr("type", "radio")
				.attr("id", "toggleView")
				.attr("name", "viewMode")
				.attr("title", function(d){return d3.values(d)[0];})
				.property("checked", function(d, i){ return i==slide;}); // check the one that matches the slide number

    d3.selectAll("input#toggleView")
      .on("change", toggleView)

    function toggleView() {

        var imageName = d3.keys(d3.select(this).data()[0])[0];
        

        // update state based on number of slide
		slide = example.images.map(function(d){ return d3.keys(d)[0]}).indexOf(imageName);
		
		pushState(slide);
		
    	svg.select("image")
    		.attr("xlink:href", function(d){ return example.githubBase + "/" + imageName;});
		
		zoomtoextents();
    }

	/*
	// add versions

	// add options based on input and outputs
    d3.select("select")
    	.text("version: ")
    	.selectAll("option")
      	.data(example.versions.reverse())
      	.enter()
        	.append("option")
        	.attr("value", function (d) { return d; })
        		.text(function (d) { return d; });

    // add change event to resort images
    d3.select("select").on("change", function(d) {
        version = d3.select(this).property("value");
        //console.log(version);
        // image, description and change log needs to be updated
    })

	*/

	// script for zooming and panning .png images
	
	var imgWidth = 960, imgHeight = 480,		// Image dimensions (don't change these)
	    width =  $('#imagecontainer').width(), height = 480,        // Dimensions of cropped region
	    //width =  960, height = 480,        // Dimensions of cropped region
	    padding = 10,
	    translate0 = offset,
		scale0 = scale;

	svg = d3.select("#imageContainer").append("svg")
	    .attr("width",  (width - (2.5 * padding)) + "px")
	    .attr("height", height + "px");

	var zoom = d3.behavior.zoom().scaleExtent([1, 8]).on("zoom", zoom);

	svg = svg.call(zoom)
	  .append("g");

	svg.append("image")
	    .attr("width",  (imgWidth - (2.5 * padding)) + "px")
	    .attr("height", imgHeight + "px")
	    .attr("xlink:href", function(){ return example.githubBase + "/" + d3.keys(example.images[slide])[0]});

	if (typeof example.videos === 'object'){
		d3.select("#videoContainer")
			.selectAll("iframe")
				.data(example.videos)
				.enter()
					.append("iframe")
				    .attr("width",  (width - (2.5 * padding)) + "px")
				    .attr("height", height + "px")
				    .attr("src", function(d){ return d3.keys(d)[0];})
				    .attr("title", function(d){ return d3.values(d)[0];})
				    .attr("frameborder", "0");
				    //.property("allowfullscreen");
	}

	function pushState(slide){
		// update global variables
		slide = slide;

		var newState = document.location.pathname + "?owner=" + example.owner + "&fork=" + example.fork + "&id=" + example.description + "&slide=" + slide + "&scale=" + scale + 
		"&offset=" + offset.join(",");

		history.pushState(null, null, newState);
	}

	function replaceState(scale, offset){
		// update global variables
		scale = scale;
		offset = offset;
		var newState = document.location.pathname + "?owner=" + example.owner + "&fork=" + example.fork + "&id=" + example.description + "&slide=" + slide + "&scale=" + scale + 
		"&offset=" + offset.join(",");

		history.replaceState(null, null, newState);
	}

	function zoom() {
	 	svg.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
		replaceState(d3.event.scale, d3.event.translate);	  
	};

	function zoomtoextents(){
		svg.call(zoom.scale(1).translate([0,0]));
		svg.attr("transform", "translate(0,0)scale(1)");
		replaceState(1, [0,0]);
	}

	function initialzoom(){
		svg.call(zoom.scale(scale0).translate(translate0));
		svg.attr("transform", "translate(" + translate0 + ")scale(" + scale0 + ")");
		replaceState(scale0, translate0);	  
	}
	
	initialzoom();
	
	d3.select("#zoomtoextents").on("click", zoomtoextents);

	// script for tags
	d3.select(".example-tags").selectAll("a")
		.data(example.tags)
		.enter()
			.append("a")
			.attr("href", function(d){ return searchwkeywordbasepath + d})
	  		.text(function(d){ return "#" + d + " "});

	// script for readme
	var readme = d3.select(".example-readme");
    d3.text(example.readme_path, function(error, content) {
        readme.html(new Showdown.converter().makeHtml(content));
        readme.selectAll("code").each(function() { hljs.highlightBlock(this); });
    });

    // add list of components
    d3.select(".example-dependencies")
    	.selectAll("span")
    	.data(example.dependencies)
    	.enter()
    		.append("span")
    		.attr("class", "dependencies")
    		.text(function(d){return " " + d + " ";});

    // add list of components
    d3.select(".example-components")
    	.selectAll("span")
    	.data(d3.keys(example.components))
    	.enter()
    		.append("span")
    		.attr("class", "components")
    		.text(function(d){return " " + example.components[d] + " X " + d + " "})
}
</script>