			<span id="last_updated" style="font-size: .45em; font-weight: normal;"></span>
			<div></div>
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
		<h1>Pylos</h1>
		<p>Pylos is a collection of sharable computational design, environmental, and sustainable analysis tools, components, definitions, plugins, and other bits known as blocks. <a href="#" onclick="d3.select('#more').style('display','inline'); d3.select(this).remove(); ">Learn more and help...</a><span id="more" style="display:none">It was inspired by <a href="http://bl.ocks.org" target="_blank">Blocks</a> and <a href="https://github.com/HydraShare/hydra/wiki" target="_blank">Hydrashare</a> and is currently maintained by the computational design group and PPT. Learn how to <a href="./start.html">share your own blocks</a> or get <a href="./start.html">help</a>.</span></p>
		<p>
			Start here: <a href="start.html">Get Started</a>, <a href="start.html">Add yours!</a>, <a href="./hydra?keywords=install">Install Components</a><br />
			Quick categories: <a href="./hydra?keywords=grasshopper">Grasshopper</a>, <a href="./hydra?keywords=dynamo">Dynamo</a>, <a href="./hydra?keywords=diva">diva</a>
		</p>
	</header>
	<div class="examples">
	  <br clear="both">
	  <div class="loading">Loading blocks; here we go...</div>
	</div>
	<div id="load_time"></div>


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
          .attr("class", "example")
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
</body>
</html>