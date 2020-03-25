<!-- Content Area -->
		<div class="row">
			<div class="col-lg-12 examples grid-filter">
				<div class="row">
					<div class="col-lg-2"><h3 style="margin-top: 0;">Blocks</h3></div>
					<div class="col-lg-8"><p style="font-size: 100%;">This is a collection of blocks from Hydrashare, a repository of tools started by the guys behind the Ladybug and Honeybee tools.</p></div>
				</div>			
				<div class="loading"><img src="/includes/app/pylos/loading.svg" style="height: 23px;width: 23px;margin-right: 9px;vertical-align: middle;"> Loading blocks; here we go...</div>
			</div>
		</div>
		
<!-- End Content Area -->



					</div><!-- this closes the main content region -->
					<div class="col-sm-3">
						<div class="headline-parent"><h3 class="text-left headline">Tools</h3></div>
						<h5>Meta</h5>
						<p><span id="last_updated"></span></p>
						<p><span id="load_time"></span></p>
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


<script type="text/javascript">

    // read all the available example files
    var formatDate = d3.time.format("%B %-d, %Y"),
        parseDate = d3.time.format.iso.parse,
        sortBy = "created_at",
        page = 0,
        objectPerPage = 30, // number of objects to be loaded per page
        keywords = [],
        visibility = [], // 0-1 values for visibility after filtering
        fetching,
        data;

    // parse Uri to get the keywords onload
    var keywords = parseUri();

    // update value in text box
    if (keywords.length != 0){
        // d3 equivalent didn't work!
        document.getElementById("filtering").value = keywords.join(", ");
    }

    // Get the blocks from the api, filter based on keywords and display first set
    jQuery(document).ready(loadBlocks());

    d3.select(window)
      .on("scroll", maybeFetch)
      .on("resize", maybeFetch);

    function maybeFetch() {
      if (!fetching && page >= 0 && d3.select(".loading").node().getBoundingClientRect().top < innerHeight) {
          fetch();
        }
      }

    function fetch() {
      fetching = true;
      display(null, filter());
    }

    function display(error, data) {
      fetching = false;

      if (data.length < objectPerPage * (page -1)) {
          // it's all already loaded to the page
          page = -1;
          d3.select(".loading").text("");
          return;
      }

      // remove divs on filter - not the best practice but works for now
      if (page == 0) d3.select(".examples").selectAll(".example").remove();

      ++page;

      var exampleEnter = d3.select(".examples").selectAll(".example")
          .data(data)
        .enter().insert("a", "br")
          .attr("class", "example grid-filter-single")
          .attr("title", function(d) {return "By " + d.owner + " <" + d.created_at + ">";})
          .attr("href", function(d) { return document.location.origin + "/pylos/single?owner=" + d.owner + "&fork=" + d.fork + "&id=" + encodeURIComponent(d.id); })
            .style("background-image", function(d) { return "url(" + d.thumbnail + ")";});

      exampleEnter.append("span")
          .attr("class", "description")
          .text(function(d) { return d.id.replace(/_/g, " "); });

      exampleEnter.append("span")
          .attr("class", "date")
          .text(function(d) { return d.created_at; });

      setTimeout(maybeFetch, 50);
    }

    // add add sort functionality
    d3.select("select").on("change", function() {
          sortBy = d3.select(this).property("value");
          sortBlocks(sortBy);
          });

    d3.select('#filtering').on("keypress", function(){
        var keyCode =d3.event.keyCode;
        if (keyCode == '13') {
          page = 0; // starting over
          display(null, filter());
        }
      });

</script>
