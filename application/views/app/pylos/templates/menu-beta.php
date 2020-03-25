<!--
<div class="col-lg-7 col-md-12 hidden-xs">
	<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos/taxonomy"); ?>" style="color:#3e3f3a;">Collections</a></div></div>
	<h5><a href="<?php echo site_url("pylos/strategies"); ?>">Strategies</a></h5>
	<h5><a href="<?php echo site_url("pylos/taxonomy"); ?>">Themes</a></h5>
	<h5><a href="<?php echo site_url("pylos/taxonomy/create"); ?>"><i>Create a theme &rarr;</i></a></h5>
	<hr />
	<h5><a href="<?php echo site_url("pylos/tags/example"); ?>">Example Files</a></h5>
	<h5><a href="<?php echo site_url("pylos/tags/grasshopper"); ?>">Grasshopper</a></h5>
	<h5><a href="<?php echo site_url("pylos/tags/all"); ?>"><i>See all tags &rarr;</i></a></h5>
	<h5><a href="<?php echo site_url("pylos/dependencies/all"); ?>"><i>Dependencies &rarr;</i></a></h5>
	<div class="headline-headline"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Resources</a></div></div>
	<h5><a href="<?php echo site_url("pylos/guides"); ?>">Guides</a> or <a href="<?php echo site_url("pylos/guides/create"); ?>"><i>new &rarr;</i></a></h5>
	<h5><a href="<?php echo site_url("pylos/blocks"); ?>">Blocks</a> or <a href="<?php echo site_url("pylos/blocks/create"); ?>"><i>new &rarr;</i></a></h5>
	<h5><a href="<?php echo site_url("pylos/presentations"); ?>">Presentations</a> or <a href="<?php echo site_url("pylos/presentations/create"); ?>"><i>new &rarr;</i></a></h5>
</div>
-->
<?php if (!isset($section[0])) $section = array(false,false); ?> 
<div class="row">
	<div class="col-xs-5">
		<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Pylos</a></div></div>
		<ul class="site-nav-pills" role="tablist">
			<li role="presentation" class="<?php if ($section[0] == 'start') echo 'active'; ?>"><a href="#menu-start" aria-controls="menu-start" role="tab" data-toggle="tab" aria-expanded="false">Start Here</a></li>
			<li role="presentation" class="<?php if ($section[0] == 'search') echo 'active'; ?>"><a href="#menu-search" aria-controls="menu-search" role="tab" data-toggle="tab" aria-expanded="false">Search</a></li>
			<li role="presentation" class="<?php if ($section[0] == 'strategies') echo 'active'; ?>"><a href="#menu-strategies" aria-controls="menu-strategies" role="tab" data-toggle="tab" aria-expanded="false">Strategies</a></li>
			<li role="presentation" class="<?php if ($section[0] == 'library') echo 'active'; ?>"><a href="#menu-resources" aria-controls="menu-resources" role="tab" data-toggle="tab" aria-expanded="false">Resource Library</a></li>
			<li role="presentation" class="<?php if ($section[0] == 'tools') echo 'active'; ?>"><a href="#menu-tools" aria-controls="menu-tools" role="tab" data-toggle="tab" aria-expanded="false">Tools &amp; Dependencies</a></li>
			<li role="presentation" class="<?php if ($section[0] == 'apps') echo 'active'; ?>"><a href="#menu-apps" aria-controls="menu-apps" role="tab" data-toggle="tab" aria-expanded="false">Apps</a></li>
			<hr>
			<li role="presentation" class="<?php if ($section[0] == 'about') echo 'active'; ?>"><a href="#menu-about" aria-controls="menu-about" role="tab" data-toggle="tab" aria-expanded="false">About This Site</a></li>
			<?php if($this->ion_auth->logged_in()) { ?><li role="presentation" class="<?php if ($section[0] == 'admin') echo 'active'; ?>"><a href="#menu-admin" aria-controls="menu-admin" role="tab" data-toggle="tab" aria-expanded="false">Site Tools</a></li>
			<li><a href="/auth/logout" onclick="$(this).text('See ya later...');">Sign Out</a></li>
			<?php } else { ?><li role="presentation" class="<?php if ($section[0] == false) echo 'active'; ?>"><a href="#menu-auth" aria-controls="menu-auth" role="tab" data-toggle="tab" aria-expanded="false">Sign In</a></li><?php } ?>
		</ul>
	</div>
	<div class="col-xs-7">
		<div class="tab-content site-nav-tabs">
			<!-- Tab - start -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'start') echo ' active in'; ?>" id="menu-start">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">For PA's and PM's</a></div></div>
				<p>Understand the what, when, and how of pushing your projects with resources sorted by phase.
				<a href="/pylos/phases" class="site-nav-link">Strategies by Phase</a></p>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/tools"); ?>" style="color:#3e3f3a;">Design Resources</a></div>
				<p>Maybe a single sentence on the principles with a link to its page. <a href="/taxonomy" class="site-nav-link">Governing Feature</a></p>
			</div>
			<!-- /Tab -->
			<!-- Tab - search -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'search') echo ' active in'; ?>" id="menu-search">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Search</a></div></div>
				<p>Quickly dive into the resource library and the rest of the content in Pylos.</p>
				<p><b>Cool search placeholder!</b> You can't search from this menu yet, (I know, I know...), head to the <a href="<?php echo site_url("pylos"); ?>">homepage</a> to search Pylos.</p>
				<div class="clear"></div>
			</div>
			<!-- /Tab -->
			<!-- Tab - map -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'strategies') echo ' active in'; ?>" id="menu-strategies">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Phases</a></div></div>
				<p>Explore strategies by project phase and see when and how to best engage with your projects. <a href="/pylos/phases" class="site-nav-link">See the analysis menu</a></p>
				<ul class="site-nav-promoted-list i-fixed">
					<li><a href="<?php echo site_url("pylos/phases/programming"); ?>"><i class="fa fa-sitemap"></i> Programming</a></li>
					<li><a href="<?php echo site_url("pylos/phases/pre-design"); ?>"><i class="fa fa-cubes"></i> Pre Design</a></li>
					<li><a href="<?php echo site_url("pylos/phases/schematic-design"); ?>"><i class="fa fa-paint-brush"></i> Schematic Design</a></li>
					<li><a href="<?php echo site_url("pylos/phases/design-development"); ?>"><i class="fa fa-pencil"></i> Design Development</a></li>
					<li><a href="<?php echo site_url("pylos/phases/construction-documents"); ?>"><i class="fa fa-newspaper-o"></i> Construction Documents</a></li>
					<li><a href="<?php echo site_url("pylos/phases/construction-administration"); ?>"><i class="fa fa-life-saver"></i> Construction Administration</a></li>
					<li><a href="<?php echo site_url("pylos/phases/procurement"); ?>"><i class="fa fa-shopping-cart"></i> Procurement</a></li>
					<li><a href="<?php echo site_url("pylos/phases/post-construction"); ?>"><i class="fa fa-key"></i> Post Construction</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Themes</a></div>
				<p>You can also find design and analysis strategies related to a specific theme or certification category. <a href="/pylos/themes" class="site-nav-link">More about themes</a></p>
				<ul class="site-nav-list">
					<?php foreach ($this->shared->get_data2('pylos_taxonomy',false,array('type'=>'theme'),true,array('title','slug')) as $menu_theme) { ?><li><a href="<?php echo site_url("pylos/themes/".$menu_theme['slug']); ?>"><?php echo $menu_theme['title']; ?></a></li><?php } ?> 
				</ul>
				<div class="clear"></div>
			</div>
			<!-- /Tab -->
			<!-- Tab - urbanism -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'library') echo ' active in'; ?>" id="menu-resources">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos/guides"); ?>" style="color:#3e3f3a;">Guides</a></div></div>
				<p>Tutorials with easy to follow steps that take you from question to answer for a variety of tasks and analysis. <a href="/pylos/guides" class="site-nav-link">Browse guides</a></p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/guides/create"); ?>"><i class="fa fa-plus"></i> Upload a New Guide</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/blocks"); ?>" style="color:#3e3f3a;">Blocks</a></div>
				<p>These are the building blocks of computational design that you can plug into your tool, analysis, or design process already made.  <a href="/pylos/blocks" class="site-nav-link">Learn about blocks</a></p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/tags/example"); ?>"><i class="fa fa-tags"></i> Example Files</a></li>
					<li><a href="<?php echo site_url("pylos/tags/grasshopper"); ?>"><i class="fa fa-tags"></i> Grasshopper</a></li>
					<li><a href="<?php echo site_url("pylos/blocks/create"); ?>"><i class="fa fa-plus"></i> Upload a New Block</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/presentations"); ?>" style="color:#3e3f3a;">Presentations &amp; Samples</a></div>
				<p>This is a collection of presentations, workshops, and lunch &amp; learns that can help you boost your knowledge about high performance design. <a href="/pylos/presentations" class="site-nav-link">Explore presentations</a></p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/tags/lunch and learns"); ?>"><i class="fa fa-tags"></i> Lunch and Learns</a></li>
					<li><a href="<?php echo site_url("pylos/tags/workshops"); ?>"><i class="fa fa-tags"></i> Workshops</a></li>
					<li><a href="<?php echo site_url("pylos/tags/samples"); ?>"><i class="fa fa-tags"></i> Documentation Samples</a></li>
					<li><a href="<?php echo site_url("pylos/presentations/create"); ?>"><i class="fa fa-plus"></i> Upload a New Presentation</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<!-- /Tab -->
			<!-- Tab - people -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'tools') echo ' active in'; ?>" id="menu-tools">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos/tools"); ?>" style="color:#3e3f3a;">Tools</a></div></div>
				<p>Dive into the programs, plugins, scripts, and in-house tools ZGF has to push project design and performance. <a href="/pylos/guides" class="site-nav-link">Learn about tools at ZGF</a></p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/guides/create"); ?>"><i class="fa fa-plus"></i> Upload a New Guide</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/blocks"); ?>" style="color:#3e3f3a;">Dependencies</a></div>
				<p>Tools and dependencies are the same thing in Pylos except that some blocks or guides require specific tools to work. Those are dependencies.  <a href="/pylos/blocks" class="site-nav-link">Finding and installing dependencies</a></p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/tags/example"); ?>"><i class="fa fa-tags"></i> Example/tutorial resources</a></li>
					<li><a href="<?php echo site_url("pylos/revit-addins"); ?>"><i class="fa fa-flag-checkered"></i> Get started with Revit addins</a></li>
					<li><a href="<?php echo site_url("pylos/grasshopper-plugins"); ?>"><i class="fa fa-flag-checkered"></i> Get started with Grasshopper plugins</a></li>
					<li><a href="<?php echo site_url("pylos/blocks/create"); ?>"><i class="fa fa-plus"></i> Suggest a plugin/addin</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/presentations"); ?>" style="color:#3e3f3a;">Troubleshooting</a></div>
				<p>Getting new tools and dependencies to work can require a little help. Help track issues for tools that dont work and suggest new tools we should have on Pylos.</p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos/tags/lunch and learns"); ?>"><i class="fa fa-plus"></i> Suggest a tool</a></li>
					<li><a href="<?php echo site_url("pylos/tags/workshops"); ?>"><i class="fa fa-list-ul"></i> Tools Issues List</a></li>
					<li><a href="<?php echo site_url("pylos/tags/samples"); ?>"><i class="fa fa-list-ul"></i> Block/Guide Issue List</a></li>
					<li><a href="<?php echo site_url("pylos/presentations/create"); ?>"><i class="fa fa-info-circle"></i> Tracking Issues on Pylos</a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<!-- /Tab -->

			<!-- Tab - interactives -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'apps') echo ' active in'; ?>" id="menu-apps">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Apps on Pylos</a></div></div>
				<p>There are a number of apps that have been adapted for use in Pylos from custom tools to manage Revit in the cloud to external tools like Design Explorer.
				<br><a href="/diagrams" class="site-nav-link">Using and Creating Apps</a></p>
				<div class="text-left headline"><a href="<?php echo site_url("designexplorer"); ?>" style="color:#3e3f3a;">Design Explorer</a></div>
				<p>From contributing and sharing resources to assisting with the platform itself, we are always looking for a helping hand.</p>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("designexplorer"); ?>"><i class="fa fa-bar-chart"></i> My Datasets</a></li>
					<li><a href="<?php echo site_url("designexplorer/shared"); ?>"><i class="fa fa-hand-peace-o"></i> Shared Datasets</a></li>
					<li><a href="<?php echo site_url("designexplorer/create"); ?>"><i class="fa fa-plus"></i> Upload a New Dataset</a></li>
					<li><a href="<?php echo site_url("pylos/#guide-on-using-colibri-and-uploading-content"); ?>"><i class="fa fa-mortar-board"></i> Get Started &rarr;</a></li>
				</ul>
				<div class="clear"></div>
				<div class="text-left headline"><a href="<?php echo site_url("charts"); ?>" style="color:#3e3f3a;">Charts</a></div>
				<p>Paste in your spreadsheet data and easily create custom data visualizations. <a href="<?php echo site_url("charts"); ?>" class="site-nav-link">Launch Charts</a></p>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/hydra"); ?>" style="color:#3e3f3a;">Hydrashare</a></div>
				<p>The precursor of Pylos is Hydrashare, a place for the makers of Ladybug and Honeybee to share example scripts. <a href="<?php echo site_url("pylos/hydra"); ?>" class="site-nav-link">Hydrashare on Pylos</a></p>
				<div class="text-left headline"><a href="<?php echo site_url("app/lemur"); ?>" style="color:#3e3f3a;">Forge</a></div>
				<p>We are building out custom tools that can audit and modify Revit projects in the cloud, they will show up here. <a href="<?php echo site_url("app/lemur"); ?>" class="site-nav-link">ZGF Forge Apps</a></p>
			</div>
			<!-- /Tab -->
			<!-- Tab - about -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'about') echo ' active in'; ?>" id="menu-about">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("about"); ?>" style="color:#3e3f3a;">About Pylos</a></div></div>
				<p>Pylos is a platform for sharing design tools, tutorials, and guides intended to make computational and high performance design more accessible. </p>
				<li><a href="/get-started">Get Started with Pylos</a></li>
				<li><a href="/roadmap">Development Roadmap</a></li>
				<li><a href="/status">Project Status</a></li>
				<div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Get Involved</a></div>
				<p>From contributing and sharing resources to assisting with the platform itself, we are always looking for a helping hand. <a href="contribute" class="site-nav-link">Help Build Pylos</a></p>
				<div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">By the PPT + CD</a></div>
				<p>Pylos is the product of the <i>Computational Design Group</i> and the <i>Project Performance Team (PPT)</i> as well as everyone who has contributed.</p>
				<li><a href="/about">Computational Design Group</a></li>
				<li><a href="/about">PPT on Zaxis</a></li>
				<li><a href="/about">PPT on Teams</a></li>
			</div>
			<!-- /Tab -->
			<?php if($this->ion_auth->logged_in()) { ?>
			<!-- Tab - admin -->
			<div role="tabpanel" class="tab-pane fade<?php if ($section[0] == 'admin') echo ' active in'; ?>" id="menu-admin">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Admin Tools</a></div></div>
				<p>Hey Sean, feel free to edit and add to the site with these tools</p>
				<li><a data-toggle="modal" data-target="#pageeditor"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit this page</a></li>
				<li><a data-toggle="modal" data-target="#createlink" href="#"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span> Add a feed item</a></li>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/tools"); ?>" style="color:#3e3f3a;">Add Content</a></div>
				<ul class="site-nav-list i-fixed">
					<li><a data-toggle="modal" data-target="#createdefinition"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> New Definition</a></li>
					<li><a data-toggle="modal" data-target="#createtaxonomy" href="#"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> New Taxonomy (or collection)</a></li>
					<li><a data-toggle="modal" data-target="#createpage" href="#"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Page</a></li>
				</ul>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/tools"); ?>" style="color:#3e3f3a;">Site Map</a></div>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pylos"); ?>">Governing Principles</a></li>
				</ul>
				<ul class="site-nav-list i-fixed">
					<li><a href="<?php echo site_url("pages"); ?>">View all Pages</a></li>
					<li><a href="<?php echo site_url("taxonomy"); ?>">View all Taxonomy/Categories/Collections</a></li>
					<li><a href="<?php echo site_url("definitions"); ?>">View all Definitions/People/Terms</a></li>
				</ul>
				<div class="text-left headline"><a href="<?php echo site_url("pylos/tools"); ?>" style="color:#3e3f3a;">Platform</a></div>
				<ul class="site-nav-list i-fixed">
					<li><a href="/auth">User Administration</a></li>
					<li><a href="/pylos/admin">Site Administration</a></li>
					<li><a href="/help">Help and Change Log</a></li>
				</ul>
			</div>
			<!-- /Tab -->
			<?php } else { ?>
			<!-- Tab - asdasd -->
			<div role="tabpanel" class="tab-pane fade" id="menu-auth">
				<div class="headline-parent"><div class="text-left headline"><a href="<?php echo site_url("pylos"); ?>" style="color:#3e3f3a;">Engage with Pylos</a></div></div>
				<?php echo form_open("auth/login");?>
				<div class="panel panel-default">
					<div class="panel-body">
						<a href="/auth/saml/login/?return=<?php echo uri_string(); ?>" class="btn btn-sm btn-primary btn-block btn-disabled" onclick="$(this).text('signing you in...');">Sign in &rarr;</a>
					</div>
				</div>
				<p>or...</p>
				<div class="panel panel-default">
					<div class="panel-body" style="font-size: 13px;">
						<label for="identity" class="sr-only">Email address</label>
						<input type="email" id="identity" name="identity" class="form-control" placeholder="Email address" required autofocus>
						<label for="inputPassword" class="sr-only">Password</label>
						<input type="password" name="password" class="form-control" placeholder="Password" required>
						<label><a href="/auth/forgot_password">Forgot your password?</a></label>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember" value="1"> Remember me
							</label>
						</div>
						<div class="btn-group btn-block" role="group" aria-label="logincreateaccount">
							<button class="btn btn-sm btn-info btn-block" style="margin-top:0;" type="submit" data-loading-text="checking...">Log in &rarr;</button>
						</div>
					</div>
				</div>
				</form>
			</div>
			<!-- /Tab -->
			<?php } ?> 		</div>
	</div>
</div>
